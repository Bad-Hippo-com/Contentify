<?php

namespace Tests\Unit;

use Contentify\DashboardFeed;
use PHPUnit\Framework\TestCase;

class DashboardFeedTest extends TestCase
{
    public function test_it_normalizes_valid_messages(): void
    {
        $messages = DashboardFeed::decode(json_encode([
            [
                'url'       => 'https://github.com/Bad-Hippo-com/Contentify',
                'text'      => ' Bad Hippo 3.3-dev ',
                'timestamp' => 1788719159,
                'icon'      => 'code-branch',
            ],
        ]), 'https://github.com/Bad-Hippo-com/Contentify');

        $this->assertCount(1, $messages);
        $this->assertSame('Bad Hippo 3.3-dev', $messages[0]->text);
        $this->assertSame('https://github.com/Bad-Hippo-com/Contentify', $messages[0]->url);
        $this->assertSame(1788719159, $messages[0]->timestamp);
        $this->assertSame('code-branch', $messages[0]->icon);
    }

    public function test_it_rejects_unsafe_fields_and_skips_broken_messages(): void
    {
        $fallbackUrl = 'https://github.com/Bad-Hippo-com/Contentify';
        $messages = DashboardFeed::decode(json_encode([
            [
                'url'       => 'javascript:alert(1)',
                'text'      => '<script>alert(1)</script>',
                'timestamp' => 1788719159,
                'icon'      => 'cloud\" onclick=\"alert(1)',
            ],
            ['text' => '', 'timestamp' => 0],
            ['url' => $fallbackUrl],
        ]), $fallbackUrl);

        $this->assertCount(1, $messages);
        $this->assertSame($fallbackUrl, $messages[0]->url);
        $this->assertSame('info-circle', $messages[0]->icon);
        $this->assertSame('<script>alert(1)</script>', $messages[0]->text);
    }

    public function test_it_marks_invalid_json_as_invalid(): void
    {
        $this->assertNull(DashboardFeed::decode('{broken', 'https://github.com/Bad-Hippo-com/Contentify'));
        $this->assertNull(DashboardFeed::decode('{"message":"not a list"}', 'https://github.com/Bad-Hippo-com/Contentify'));
    }
}
