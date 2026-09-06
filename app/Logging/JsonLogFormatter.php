<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter;

class JsonLogFormatter
{
    /**
     * Format Laravel application logs as one JSON object per line.
     *
     * @param mixed $logger
     * @return void
     */
    public function __invoke($logger)
    {
        $logger->pushProcessor(function ($record) {
            $record['extra']['environment'] = app()->environment();
            $record['extra']['host'] = gethostname();
            $record['extra']['build_version'] = config('app.build_version');

            if (app()->bound('request')) {
                $request = app('request');
                $record['extra']['request_id'] = $request->headers->get('X-Request-ID');
                $record['extra']['method'] = $request->method();
                $record['extra']['path'] = $request->path();
            }

            return $record;
        });

        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new JsonFormatter(JsonFormatter::BATCH_MODE_JSON, true));
        }
    }
}
