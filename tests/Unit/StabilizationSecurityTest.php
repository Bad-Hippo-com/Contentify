<?php

namespace Tests\Unit;

use App\Exceptions\Handler;
use App\Http\Middleware\ConfirmMutation;
use App\Modules\Cups\CupMatch;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class StabilizationSecurityTest extends TestCase
{
    public function testNullableOriginalDatesRemainNullable(): void
    {
        $match = new \App\Modules\Cups\CupMatch;
        $this->assertNull($match->fromDateTime(null));
        $this->assertSame('', $match->fromDateTime(''));
    }
    public function testLocalAssetsAreVersionedWithoutChangingExternalUrls(): void
    {
        $builder = app('html');
        $version = trim(file_get_contents(base_path('VERSION')));
        $this->assertStringContainsString('comments.js?v='.$version, (string) $builder->script('vendor/contentify/comments.js'));
        $this->assertStringContainsString('backend.css?v='.$version, (string) $builder->style('css/backend.css'));
        $this->assertStringNotContainsString('?v=', (string) $builder->script('https://example.com/test.js'));
    }
    public function testOnlyRecipientCanConfirmFriendRequest(): void
    {
        $friendship = new \App\Modules\Friends\Friendship;
        $friendship->setRawAttributes(['sender_id' => 7, 'receiver_id' => 8, 'confirmed' => 0]);
        $this->assertFalse($friendship->canBeConfirmedBy(7));
        $this->assertFalse($friendship->canBeConfirmedBy(9));
        $this->assertTrue($friendship->canBeConfirmedBy(8));
        $friendship->confirmed = 1;
        $this->assertFalse($friendship->canBeConfirmedBy(8));
    }
    public function testCommentOwnerCanEditWithoutChangingOwnershipOrContext(): void
    {
        $comment = Mockery::mock(\Contentify\Models\Comment::class)->makePartial();
        $comment->setRawAttributes(['id' => 5, 'creator_id' => 7, 'foreign_type' => 'news', 'foreign_id' => 42, 'text' => 'Vorher']);
        $comment->shouldReceive('save')->once()->andReturn(true);
        $user = Mockery::mock(\Contentify\Models\User::class)->makePartial();
        $user->setRawAttributes(['id' => 7]);
        $user->shouldReceive('hasAccess')->with('comments', PERM_UPDATE)->andReturn(false);
        \Sentinel::shouldReceive('getUser')->andReturn($user);
        app('request')->replace(['text' => 'Nachher', 'creator_id' => 99, 'foreign_id' => 99]);
        $comments = new class($comment) extends \Contentify\Comments {
            public function __construct(private \Contentify\Models\Comment $fixture) {}
            protected function findComment(int $id): \Contentify\Models\Comment { return $this->fixture; }
        };
        $view = $comments->update(5);
        $this->assertSame('comments.comment', $view->name());
        $this->assertSame('news', $view->getData()['foreignType']);
        $this->assertEquals(42, $view->getData()['foreignId']);
        $this->assertEquals(7, $comment->creator_id);
        $this->assertSame('Nachher', $comment->text);
    }

    public function testOtherUserCannotChangeOrDeleteComment(): void
    {
        $comment = Mockery::mock(\Contentify\Models\Comment::class)->makePartial();
        $comment->setRawAttributes(['id' => 5, 'creator_id' => 7]);
        $comment->shouldNotReceive('save');
        $comment->shouldNotReceive('delete');
        $user = Mockery::mock(\Contentify\Models\User::class)->makePartial();
        $user->setRawAttributes(['id' => 8]);
        $user->shouldReceive('hasAccess')->andReturn(false);
        \Sentinel::shouldReceive('getUser')->andReturn($user);
        $comments = new class($comment) extends \Contentify\Comments {
            public function __construct(private \Contentify\Models\Comment $fixture) {}
            protected function findComment(int $id): \Contentify\Models\Comment { return $this->fixture; }
        };
        $this->assertSame(403, $comments->update(5)->getStatusCode());
        $this->assertSame(403, $comments->delete(5)->getStatusCode());
    }

    public function testPostWithoutCsrfTokenIsRejected(): void
    {
        $middleware = new class(app(), app('encrypter')) extends \App\Http\Middleware\VerifyCsrfToken {
            protected function runningUnitTests() { return false; }
        };
        $request = Request::create('/cups/check-in/1', 'POST');
        $request->setLaravelSession(app('session.store'));
        $this->expectException(\Illuminate\Session\TokenMismatchException::class);
        $middleware->handle($request, function () { $this->fail('Missing CSRF token accepted.'); });
    }

    public function testHttpErrorsKeepTheirStatusAndHeaders(): void
    {
        config(['app.debug' => false]);
        foreach ([403, 404, 405, 419] as $status) {
            $request = Request::create('/missing', 'GET', [], [], [], ['HTTP_ACCEPT' => 'application/json']);
            $response = app(Handler::class)->render($request, new HttpException($status, '', null, ['X-Test' => 'preserved']));
            $this->assertSame($status, $response->getStatusCode());
            $this->assertSame('preserved', $response->headers->get('X-Test'));
        }
    }

    public function testValidationDoesNotBecomeServerError(): void
    {
        config(['app.debug' => false]);
        $request = Request::create('/messages', 'POST', [], [], [], ['HTTP_ACCEPT' => 'application/json']);
        $response = app(Handler::class)->render($request, ValidationException::withMessages(['text' => 'Ungültig']));
        $this->assertSame(422, $response->getStatusCode());
    }

    public function testCupMutationLinksOnlyDisplayConfirmation(): void
    {
        foreach (['GET', 'HEAD'] as $method) {
            $response = (new ConfirmMutation)->handle(Request::create('/cups/check-in/42', $method), function () {
                $this->fail('A safe request must never call the mutation controller.');
            });
            $this->assertSame(200, $response->getStatusCode());
            $this->assertStringContainsString('method="post"', $response->getContent());
            $this->assertStringContainsString('_token', $response->getContent());
        }
        $response = (new ConfirmMutation)->handle(Request::create('/cups/check-in/42', 'POST'), fn () => response('confirmed'));
        $this->assertSame('confirmed', $response->getContent());
    }

    public function testCupMutationRoutesRequireAuthenticationAndConfirmation(): void
    {
        foreach (['cups/join/1/2', 'cups/check-in/1', 'cups/check-out/1', 'admin/cups/seed/1',
            'admin/cups/participants/delete/1/2', 'cups/teams/leave/1/2', 'cups/teams/delete/1',
            'forums/threads/sticky/1', 'forums/threads/closed/1', 'forums/threads/delete/1',
            'forums/posts/delete/1', 'forums/posts/report/1', 'friends/add/1', 'friends/confirm/1',
            'admin/activities/delete/all', 'admin/config/log/clear', 'admin/config/optimize',
            'admin/config/compile-less', 'admin/config/clear-cache'] as $uri) {
            foreach (['GET', 'POST'] as $method) {
                $route = app('router')->getRoutes()->match(Request::create('/'.$uri, $method));
                $this->assertContains('auth', $route->gatherMiddleware());
                $this->assertContains(ConfirmMutation::class, $route->gatherMiddleware());
            }
        }
    }

    public function testAdminRestoreRoutesAcceptPostButNeverGet(): void
    {
        $restoreRoutes = collect(app('router')->getRoutes())->filter(function ($route) {
            return str_ends_with((string) $route->getActionName(), '@restore');
        });
        $this->assertGreaterThanOrEqual(20, $restoreRoutes->count());
        foreach ($restoreRoutes as $route) {
            $this->assertContains('POST', $route->methods(), $route->uri());
            $this->assertNotContains('GET', $route->methods(), $route->uri());
        }
    }

    public function testWinnerSwitchUpdatesBothMatches(): void
    {
        $next = Mockery::mock(CupMatch::class)->makePartial();
        $next->setRawAttributes(['cup_id' => 1, 'winner_id' => 0, 'row' => 1]);
        $next->shouldReceive('forceSave')->once()->andReturn(true);
        $match = Mockery::mock(CupMatch::class)->makePartial();
        $match->setRawAttributes(['cup_id' => 1, 'left_participant_id' => 10, 'right_participant_id' => 20, 'winner_id' => 10, 'row' => 2]);
        $match->shouldReceive('nextMatch')->once()->andReturn($next);
        $match->shouldReceive('forceSave')->once()->andReturn(true);
        $match->updateWinner();
        $this->assertEquals(20, $match->winner_id);
        $this->assertEquals(20, $next->right_participant_id);
        $this->assertEquals(0, $match->left_score);
        $this->assertEquals(1, $match->right_score);
    }

    public function testFinalWithoutNextMatchCannotSwitchWinner(): void
    {
        $match = Mockery::mock(CupMatch::class)->makePartial();
        $match->setRawAttributes(['left_participant_id' => 10, 'right_participant_id' => 20, 'winner_id' => 10]);
        $match->shouldReceive('nextMatch')->once()->andReturn(null);
        $match->shouldNotReceive('forceSave');
        $this->expectException(\Contentify\Exceptions\MsgException::class);
        $match->updateWinner();
    }
}
