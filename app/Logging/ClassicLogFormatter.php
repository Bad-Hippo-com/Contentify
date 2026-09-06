<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;

class ClassicLogFormatter
{
    /**
     * Keep the administrator display copy in classic Laravel text format.
     * Operational extra fields belong only in the detailed JSON output.
     *
     * @param mixed $logger
     * @return void
     */
    public function __invoke($logger)
    {
        $format = "[%datetime%] %channel%.%level_name%: %message% %context%\n";
        $dateFormat = 'Y-m-d H:i:s';

        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new LineFormatter($format, $dateFormat, true, true));
        }
    }
}
