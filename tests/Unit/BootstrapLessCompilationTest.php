<?php

namespace Tests\Unit;

use Less_Parser;
use PHPUnit\Framework\TestCase;

class BootstrapLessCompilationTest extends TestCase
{
    public function test_backend_and_both_themes_compile_with_the_php_runtime(): void
    {
        $root = dirname(__DIR__, 2);
        foreach ([
            'resources/assets/less/backend.less',
            'app/Modules/MorpheusTheme/Resources/Assets/less/frontend.less',
            'app/Modules/PhobosTheme/Resources/Assets/less/frontend.less',
        ] as $source) {
            $parser = new Less_Parser(['compress' => true]);
            $parser->parseFile($root.'/'.$source);
            $css = $parser->getCss();
            $this->assertStringContainsString('Bootstrap v4.6.2', $css, $source);
            $this->assertStringNotContainsString('glyphicons-halflings-regular.woff2', $css, $source);
            $this->assertStringNotContainsString('.modal.in', $css, $source);
        }
    }
}
