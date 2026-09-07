<?php

namespace Tests\Unit;

use Illuminate\Foundation\Application;
use Tests\TestCase;

class Laravel12CompatibilityTest extends TestCase
{
    public function testLaravelTwelveRunsWithTheExplicitLegacyLocalDiskRoot(): void
    {
        $this->assertStringStartsWith('12.', Application::VERSION);
        $this->assertSame(storage_path('app'), config('filesystems.disks.local.root'));
    }
}
