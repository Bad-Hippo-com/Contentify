<?php

namespace Tests\Unit;

use Contentify\Controllers\BaseController;
use Illuminate\Foundation\Application;
use Tests\TestCase;

class Laravel12CompatibilityTest extends TestCase
{
    public function testLaravelTwelveRunsWithTheExplicitLegacyLocalDiskRoot(): void
    {
        $this->assertStringStartsWith('12.', Application::VERSION);
        $this->assertSame(storage_path('app'), config('filesystems.disks.local.root'));
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
