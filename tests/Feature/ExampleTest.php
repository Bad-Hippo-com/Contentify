<?php

namespace Tests\Feature;

use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    private $freshInstallMarker;

    private $freshInstallMarkerBackup;

    /**
     * Boot this test as a genuinely fresh installation, independently of a
     * staging marker that may exist in the mounted storage directory.
     */
    public function createApplication()
    {
        $this->freshInstallMarker = __DIR__.'/../../storage/app/.installed';
        $this->freshInstallMarkerBackup = $this->freshInstallMarker.'.phpunit-backup';
        $hadMarker = is_file($this->freshInstallMarker);

        if ($hadMarker) {
            rename($this->freshInstallMarker, $this->freshInstallMarkerBackup);
        }

        try {
            $app = require __DIR__.'/../../bootstrap/app.php';
            $app->make(Kernel::class)->bootstrap();
        } catch (\Throwable $exception) {
            if ($hadMarker) {
                rename($this->freshInstallMarkerBackup, $this->freshInstallMarker);
            }

            throw $exception;
        }

        return $app;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->freshInstallMarkerBackup && is_file($this->freshInstallMarkerBackup)) {
            rename($this->freshInstallMarkerBackup, $this->freshInstallMarker);
        }
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFreshInstallationRedirectsToInstaller()
    {
        $route = app('router')->getRoutes()->getByName('home');

        $this->assertNotNull($route);
        $action = $route->getAction('uses');
        $this->assertInstanceOf(\Closure::class, $action);

        $response = $action();

        $this->assertStringEndsWith('/install.php', $response->getTargetUrl());
    }
}
