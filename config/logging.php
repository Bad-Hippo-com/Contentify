<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

$logRoot = rtrim(env('CONTENTIFY_LOG_ROOT', storage_path('logs')), '/\\');
$logLevel = env('LOG_LEVEL', 'debug');
$logDays = (int) env('LOG_DAYS', 30);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['application', 'legacy'],
            'ignore_exceptions' => false,
            'tap' => [App\Logging\LogContextProcessor::class],
        ],

        'application' => [
            'driver' => 'daily',
            'path' => $logRoot.'/application.log',
            'level' => $logLevel,
            'days' => $logDays,
            'permission' => 0640,
            'locking' => true,
            'tap' => [
                App\Logging\LogContextProcessor::class,
                App\Logging\JsonLogFormatter::class,
            ],
        ],

        'security' => [
            'driver' => 'daily',
            'path' => $logRoot.'/security.log',
            'level' => env('SECURITY_LOG_LEVEL', 'notice'),
            'days' => $logDays,
            'permission' => 0640,
            'locking' => true,
            'tap' => [
                App\Logging\LogContextProcessor::class,
                App\Logging\JsonLogFormatter::class,
            ],
        ],

        'jobs' => [
            'driver' => 'daily',
            'path' => $logRoot.'/jobs.log',
            'level' => $logLevel,
            'days' => $logDays,
            'permission' => 0640,
            'locking' => true,
            'tap' => [
                App\Logging\LogContextProcessor::class,
                App\Logging\JsonLogFormatter::class,
            ],
        ],

        // Classic Laravel text log consumed by /admin/config/log.
        'legacy' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => $logLevel,
            'permission' => 0640,
            'locking' => true,
            'tap' => [App\Logging\ClassicLogFormatter::class],
        ],

        'single' => [
            'driver' => 'single',
            'path' => $logRoot.'/laravel.log',
            'level' => $logLevel,
            'permission' => 0640,
            'locking' => true,
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => $logRoot.'/laravel.log',
            'level' => $logLevel,
            'days' => $logDays,
            'permission' => 0640,
            'locking' => true,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => $logRoot.'/emergency.log',
        ],
    ],

];
