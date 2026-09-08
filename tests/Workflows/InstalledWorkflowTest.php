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
        $this->prefix = 'Flow'.bin2hex(random_bytes(5));
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
}
