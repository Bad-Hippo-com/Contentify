<?php

namespace App\Logging;

class LogContextProcessor
{
    /**
     * Add operational context before a record reaches either split output.
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
                $record['extra']['path'] = $request->is('auth/restore/new/*')
                    ? 'auth/restore/new/[redacted]' : $request->path();
            }

            return $record;
        });
    }
}
