<?php

namespace Contentify;

use Throwable;

class DiskSpace
{
    /**
     * Return the free bytes for a path, or null when the host cannot provide
     * this information (for example because of open_basedir restrictions).
     *
     * @param string $path
     * @return float|null
     */
    public static function freeBytes(string $path = '.')
    {
        if (! function_exists('disk_free_space')) {
            return null;
        }

        try {
            $bytes = @disk_free_space($path);
        } catch (Throwable $exception) {
            return null;
        }

        if ($bytes === false || ! is_numeric($bytes) || $bytes < 0) {
            return null;
        }

        return (float) $bytes;
    }
}
