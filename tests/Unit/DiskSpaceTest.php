<?php

namespace Tests\Unit;

use Contentify\DiskSpace;
use PHPUnit\Framework\TestCase;

class DiskSpaceTest extends TestCase
{
    public function test_it_returns_free_bytes_for_a_readable_path(): void
    {
        $bytes = DiskSpace::freeBytes(__DIR__);

        $this->assertIsFloat($bytes);
        $this->assertGreaterThanOrEqual(0, $bytes);
    }

    public function test_it_returns_null_when_disk_space_cannot_be_read(): void
    {
        $path = __DIR__.DIRECTORY_SEPARATOR.'missing-'.uniqid('', true);

        $this->assertNull(DiskSpace::freeBytes($path));
    }
}
