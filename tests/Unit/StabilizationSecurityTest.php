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
            'admin/cups/participants/delete/1/2', 'cups/teams/leave/1/2', 'cups/teams/delete/1'] as $uri) {
            foreach (['GET', 'POST'] as $method) {
                $route = app('router')->getRoutes()->match(Request::create('/'.$uri, $method));
                $this->assertContains('auth', $route->gatherMiddleware());
                $this->assertContains(ConfirmMutation::class, $route->gatherMiddleware());
            }
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
