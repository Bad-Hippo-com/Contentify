<?php

namespace Tests\Unit;

use Contentify\Controllers\BaseController;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class Laravel13CompatibilityTest extends TestCase
{
    public function testLaravelThirteenRunsWithExplicitCompatibilityConfiguration(): void
    {
        $this->assertStringStartsWith('13.', Application::VERSION);
        $this->assertSame(storage_path('app'), config('filesystems.disks.local.root'));
        $this->assertSame([\stdClass::class], config('cache.serializable_classes'));
        $this->assertSame('php', config('session.serialization'));
        $this->assertTrue(is_subclass_of(\App\Http\Middleware\VerifyCsrfToken::class, PreventRequestForgery::class));
    }

    public function testControllerActionsReceiveRouteParametersPositionally(): void
    {
        $controller = new class extends BaseController
        {
            public function __construct()
            {
            }

            protected function setupLayout(?string $layoutName = null)
            {
            }

            public function show($identifier): string
            {
                return 'ID:'.$identifier;
            }
        };

        $this->assertSame('ID:42', $controller->callAction('show', ['user' => 42]));
    }
}
