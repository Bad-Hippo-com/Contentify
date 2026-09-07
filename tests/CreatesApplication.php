<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    private $testInstallationMarker;

    private $testInstallationMarkerBackup;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $this->testInstallationMarker = __DIR__.'/../storage/app/.installed';
        $this->testInstallationMarkerBackup = $this->testInstallationMarker.'.phpunit-backup';
        $hadMarker = is_file($this->testInstallationMarker);

        if ($hadMarker) {
            rename($this->testInstallationMarker, $this->testInstallationMarkerBackup);
        }

        try {
            $app = require __DIR__.'/../bootstrap/app.php';

            $app->make(Kernel::class)->bootstrap();
        } catch (\Throwable $exception) {
            $this->restoreTestInstallationMarker();

            throw $exception;
        }

        return $app;
    }

    protected function restoreTestInstallationMarker(): void
    {
        if ($this->testInstallationMarkerBackup && is_file($this->testInstallationMarkerBackup)) {
            rename($this->testInstallationMarkerBackup, $this->testInstallationMarker);
        }
    }
}
