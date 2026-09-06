<?php

namespace Tests\Unit;

use Tests\TestCase;

class LoggingConfigurationTest extends TestCase
{
    public function test_default_stack_writes_detailed_and_classic_logs(): void
    {
        $this->assertSame(['application', 'legacy'], config('logging.channels.stack.channels'));
        $this->assertSame(
            [\App\Logging\LogContextProcessor::class],
            config('logging.channels.stack.tap')
        );
        $this->assertSame('daily', config('logging.channels.application.driver'));
        $this->assertSame('single', config('logging.channels.legacy.driver'));
        $this->assertSame(
            [\App\Logging\ClassicLogFormatter::class],
            config('logging.channels.legacy.tap')
        );
        $this->assertSame(
            storage_path('logs/laravel.log'),
            config('logging.channels.legacy.path')
        );
    }
}
