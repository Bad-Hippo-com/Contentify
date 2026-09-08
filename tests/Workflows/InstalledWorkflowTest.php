<?php

namespace Tests\Workflows;

use App\Modules\Messages\Message;
use Contentify\Models\Comment;
use Contentify\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Sentinel;

/** Real controllers and candidate DB, but no external mail and no committed fixtures. */
class InstalledWorkflowTest extends TestCase
{
    private string $password = 'WorkflowTest-Only-2026!';
    private string $prefix;

    public function createApplication()
    {
        if (getenv('CONTENTIFY_RUN_WORKFLOW_TESTS') !== '1' ||
            getenv('APP_URL') !== 'http://192.168.178.213:8088') {
            throw new \RuntimeException('Workflowtests sind ausschließlich im freigegebenen Kandidaten :8088 erlaubt.');
        }
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        config(['mail.default' => 'array', 'cache.default' => 'array', 'session.driver' => 'array']);
        DB::beginTransaction();
        $this->prefix = 'Flow'.bin2hex(random_bytes(3));
        Sentinel::logout();
    }

    protected function tearDown(): void
    {
        try {
            if ($this->app) {
                while (DB::transactionLevel() > 0) DB::rollBack();
            }
        } finally {
            parent::tearDown();
        }
    }

    private function fixture(string $suffix, bool $admin = false): User
    {
        $name = $this->prefix.$suffix;
        $user = Sentinel::registerAndActivate([
            'username' => $name, 'email' => $name.'@example.test',
            'password' => $this->password, 'language_id' => 1,
        ]);
        Sentinel::findRoleBySlug($admin ? 'super-admins' : 'users')->users()->attach($user);
        return $user;
    }

    private function loginAs(User $user): void
    {
        Sentinel::logout();
        Sentinel::login($user, false);
    }

    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        // PHP-FPM creates fresh controllers per request. Reproduce that boundary
        // without throwing away our transaction or mail/session test transports.
        foreach (app('router')->getRoutes() as $route) $route->flushController();
        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

    public function testRegistrationLoginAndBackendDenial(): void
    {
        $email = $this->prefix.'@example.test';
        $this->withSession(['captchaCode' => 'test'])->post('/auth/registration/create', [
            'username' => $this->prefix, 'email' => $email, 'password' => $this->password,
            'password_confirmation' => $this->password, 'captcha' => 'test',
        ]);
        $user = User::whereEmail($email)->first();
        $this->assertNotNull($user, 'Registrierung muss einen Benutzer speichern.');
        $this->assertFalse($user->isSuperAdmin());
        $this->get('/auth/logout');
        $this->post('/auth/login', ['email' => $email, 'password' => 'Incorrect-Test-Password']);
        $this->assertFalse((bool) Sentinel::check());
        $this->post('/auth/login', ['email' => $email, 'password' => $this->password]);
        $this->assertEquals($user->id, Sentinel::getUser()?->id);
        $this->get('/admin/config', ['X-Requested-With' => 'XMLHttpRequest'])->assertStatus(401);
    }

    public function testMessagesSendReadDenyThirdUserAndDelete(): void
    {
        $sender = $this->fixture('Sender');
        $receiver = $this->fixture('Receiver');
        $outsider = $this->fixture('Other');
        $this->loginAs($sender);
        $secret = $this->prefix.' Private Nachricht';
        $this->post('/messages', ['title' => $this->prefix, 'text' => $secret, 'receiver_name' => $receiver->username])
            ->assertRedirect(url('messages/outbox'));
        $message = Message::whereTitle($this->prefix)->firstOrFail();
        $this->assertEquals($receiver->id, $message->receiver_id);
        $this->assertEquals($sender->id, $message->creator_id);
        $this->loginAs($outsider);
        $this->get('/messages/'.$message->id)->assertDontSee($secret);
        $this->assertTrue((bool) $message->fresh()->new);
        $this->loginAs($receiver);
        $this->get('/messages/'.$message->id)->assertSee($secret);
        $this->assertFalse((bool) $message->fresh()->new);
        $this->delete('/messages/'.$message->id)->assertRedirect();
        $this->assertFalse((bool) $message->fresh()->receiver_visible);
        $this->loginAs($sender);
        $this->delete('/messages/'.$message->id)->assertRedirect();
        $this->assertNull(Message::find($message->id));
    }

    public function testCommentsCreateEditDenyOutsiderAndDelete(): void
    {
        $owner = $this->fixture('Owner', true);
        $outsider = $this->fixture('Other');
        $this->loginAs($owner);
        $this->post('/comments/store', ['text' => $this->prefix, 'foreigntype' => 'workflow', 'foreignid' => 1])->assertOk();
        $comment = Comment::whereText($this->prefix)->firstOrFail();
        $this->put('/comments/'.$comment->id.'/update', ['text' => $this->prefix.' geändert'])->assertOk();
        $this->assertSame($this->prefix.' geändert', $comment->fresh()->text);
        $this->loginAs($outsider);
        $this->put('/comments/'.$comment->id.'/update', ['text' => 'Fremdänderung'])->assertStatus(403);
        $this->delete('/comments/'.$comment->id.'/delete')->assertStatus(403);
        $this->assertSame($this->prefix.' geändert', $comment->fresh()->text);
        $this->loginAs($owner);
        $this->delete('/comments/'.$comment->id.'/delete')->assertOk();
        $this->assertNull(Comment::find($comment->id));
    }

    public function testLegacyPasswordResetMailCompletionAndTokenReuse(): void
    {
        $user = $this->fixture('Reset');
        $oldHash = $user->password;
        $this->withSession(['captchaCode' => 'test'])->post('/auth/restore', ['email' => $user->email, 'captcha' => 'test']);
        $reminder = DB::table('reminders')->where('user_id', $user->id)->first();
        $this->assertNotNull($reminder, 'Resetcode muss gespeichert werden.');
        $transport = Mail::mailer()->getSymfonyTransport();
        $this->assertCount(1, $transport->messages());
        $url = '/auth/restore/new/'.rawurlencode($user->email).'/'.$reminder->code;
        $this->get($url)->assertOk();
        $this->assertNotSame($oldHash, $user->fresh()->password);
        $this->assertCount(2, $transport->messages());
        $newHash = $user->fresh()->password;
        $this->get($url)->assertOk();
        $this->assertSame($newHash, $user->fresh()->password);
        $this->assertCount(2, $transport->messages());
    }

    public function testForumThreadReplyEditAndDelete(): void
    {
        $owner = $this->fixture('Forum', true);
        $this->loginAs($owner);
        $forum = new \App\Modules\Forums\Forum(['title' => $this->prefix, 'description' => 'Testforum', 'internal' => false]);
        $forum->creator_id = $owner->id;
        $forum->slug = strtolower($this->prefix);
        $forum->forceSave();
        $this->post('/forums/threads/'.$forum->id, ['title' => $this->prefix, 'text' => 'Erster Testbeitrag'])->assertRedirect();
        $thread = \App\Modules\Forums\ForumThread::whereTitle($this->prefix)->firstOrFail();
        $this->assertSame(1, \App\Modules\Forums\ForumPost::whereThreadId($thread->id)->count());
        $this->post('/forums/posts/'.$thread->id, ['text' => 'Eine Testantwort'])->assertRedirect();
        $reply = \App\Modules\Forums\ForumPost::whereThreadId($thread->id)->where('root', false)->firstOrFail();
        $this->put('/forums/posts/'.$reply->id, ['text' => 'Geänderte Testantwort'])->assertRedirect();
        $this->assertSame('Geänderte Testantwort', $reply->fresh()->text);
        $this->post('/forums/posts/delete/'.$reply->id)->assertRedirect();
        $this->assertNull(\App\Modules\Forums\ForumPost::find($reply->id));
        $this->post('/forums/threads/delete/'.$thread->id)->assertRedirect();
        $this->assertNull(\App\Modules\Forums\ForumThread::find($thread->id));
    }

    public function testCupJoinCheckInSeedAndPlayToWinner(): void
    {
        $admin = $this->fixture('Cup', true);
        $cup = new \App\Modules\Cups\Cup([
            'title' => $this->prefix, 'game_id' => \App\Modules\Games\Game::firstOrFail()->id,
            'players_per_team' => 1, 'slots' => 4, 'published' => true, 'closed' => false,
            'prize' => 'Testpreis', 'description' => 'Testcup', 'rulebook' => 'Testregeln',
        ]);
        $cup->creator_id = $admin->id;
        $cup->slug = strtolower($this->prefix);
        $cup->join_at = now()->subHour();
        $cup->check_in_at = now()->addHour();
        $cup->start_at = now()->addHours(2);
        $cup->forceSave();
        $players = [];
        for ($i = 0; $i < 4; $i++) {
            $player = $this->fixture('P'.$i);
            $players[] = $player;
            $this->loginAs($player);
            $this->post('/cups/join/'.$cup->id.'/'.$player->id)->assertRedirect();
        }
        $this->assertSame(4, $cup->participants()->count());
        $cup->check_in_at = now()->subMinute();
        $cup->forceSave();
        foreach ($players as $player) {
            $this->loginAs($player);
            $this->post('/cups/check-in/'.$cup->id)->assertRedirect();
        }
        $this->assertEquals(4, DB::table('cups_participants')->where('cup_id', $cup->id)->where('checked_in', 1)->count());
        $this->loginAs($admin);
        $this->post('/admin/cups/seed/'.$cup->id)->assertRedirect();
        $matches = \App\Modules\Cups\CupMatch::whereCupId($cup->id)->whereRound(1)->get();
        $this->assertCount(2, $matches);
        foreach ($matches as $match) {
            $this->post('/cups/matches/confirm-left/'.$match->id, ['left_score' => 2, 'right_score' => 0])->assertRedirect();
            $this->post('/cups/matches/confirm-right/'.$match->id, ['left_score' => 2, 'right_score' => 0])->assertRedirect();
        }
        $final = \App\Modules\Cups\CupMatch::whereCupId($cup->id)->whereRound(2)->firstOrFail();
        foreach ($matches as $match) $this->assertEquals($final->id, $match->fresh()->next_match_id);
        $this->post('/cups/matches/confirm-left/'.$final->id, ['left_score' => 2, 'right_score' => 0])->assertRedirect();
        $this->post('/cups/matches/confirm-right/'.$final->id, ['left_score' => 2, 'right_score' => 0])->assertRedirect();
        $this->assertEquals($final->left_participant_id, $final->fresh()->winner_id);
        $this->assertTrue((bool) $cup->fresh()->closed);
    }

    public function testInvalidUploadKeepsExistingDatabaseRow(): void
    {
        $owner = $this->fixture('Upload', true);
        $download = new \App\Modules\Downloads\Download(['title' => $this->prefix, 'download_cat_id' => 1]);
        $download->creator_id = $owner->id;
        $download->slug = strtolower($this->prefix);
        $download->forceSave();
        $file = \Illuminate\Http\UploadedFile::fake()->create('rejected.php', 1, 'text/plain');
        \Illuminate\Support\Facades\Request::swap(\Illuminate\Http\Request::create('/', 'POST', [], [], ['file' => $file]));
        $errors = (new \Contentify\Uploader)->uploadModelFiles($download, false);
        $this->assertNotEmpty($errors);
        $this->assertNotNull(\App\Modules\Downloads\Download::find($download->id), 'Abgelehnter Upload darf vorhandenen Datensatz nicht löschen.');
    }

    public function testMatchScoreCreateUpdateAndDelete(): void
    {
        $admin = $this->fixture('Match', true);
        $this->loginAs($admin);
        $game = \App\Modules\Games\Game::firstOrFail();
        $opponent = new \App\Modules\Opponents\Opponent(['title' => $this->prefix, 'short' => 'FLOW', 'lineup' => '']);
        $opponent->slug = strtolower($this->prefix);
        $opponent->forceSave();
        $map = new \App\Modules\Maps\Map(['title' => $this->prefix, 'game_id' => $game->id]);
        $map->forceSave();
        $match = new \App\Modules\Matches\GameMatch([
            'game_id' => $game->id, 'right_team_id' => $opponent->id, 'state' => 0, 'featured' => false,
            'played_at' => now(), 'text' => 'Testmatch', 'left_lineup' => '', 'right_lineup' => '',
        ]);
        $match->creator_id = $admin->id;
        $match->forceSave();
        $this->post('/admin/matches/scores/store', ['match_id' => $match->id, 'map_id' => $map->id,
            'left_score' => 2, 'right_score' => 1])->assertOk();
        $score = \App\Modules\Matches\MatchScore::whereMatchId($match->id)->firstOrFail();
        $this->assertEquals(2, $match->fresh()->left_score);
        $this->put('/admin/matches/scores/'.$score->id, ['left_score' => 3, 'right_score' => 1])->assertOk();
        $this->assertEquals(3, $match->fresh()->left_score);
        $this->delete('/admin/matches/scores/'.$score->id)->assertOk();
        $this->assertEquals(0, $match->fresh()->left_score);
    }
}
