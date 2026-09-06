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
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new JsonFormatter(JsonFormatter::BATCH_MODE_JSON, true));
        }
    }
}
