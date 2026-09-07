<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class EditorIntegrationTest extends TestCase
{
    private string $root;

    protected function setUp(): void
    {
        parent::setUp();

        $this->root = dirname(__DIR__, 2);
    }

    public function test_backend_loads_the_pinned_suneditor_assets_instead_of_ckeditor(): void
    {
        $layout = file_get_contents($this->root.'/resources/views/backend/layout_main.blade.php');
        $package = json_decode(file_get_contents($this->root.'/package.json'), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('3.3.2', $package['dependencies']['suneditor']);
        $this->assertStringContainsString('vendor/suneditor/suneditor.min.js', $layout);
        $this->assertStringContainsString('vendor/suneditor/de.js', $layout);
        $this->assertStringNotContainsString('vendor/ckeditor/ckeditor.js', $layout);
        $this->assertFileExists($this->root.'/public/vendor/suneditor/LICENSE.txt');
        $this->assertDirectoryDoesNotExist($this->root.'/public/vendor/ckeditor');
    }

    public function test_rich_textareas_are_initialized_without_inline_ckeditor_code(): void
    {
        $formBuilder = file_get_contents($this->root.'/contentify/FormBuilder.php');

        $this->assertStringContainsString("['class' => 'editor']", $formBuilder);
        $this->assertStringNotContainsString('CKEDITOR.replace', $formBuilder);
        $this->assertStringNotContainsString('custom_config.js', $formBuilder);
    }

    public function test_contentify_editor_adapter_preserves_custom_content_workflows(): void
    {
        $adapter = file_get_contents($this->root.'/public/vendor/contentify/editor.js');

        $this->assertStringContainsString("contentify.baseUrl + 'editor-images'", $adapter);
        $this->assertStringContainsString("contentify.baseUrl + 'editor-templates/'", $adapter);
        $this->assertStringContainsString("assetUrl('uploads/countries/'", $adapter);
        $this->assertStringContainsString('instance.textarea.value = currentHtml(instance)', $adapter);
        $this->assertStringContainsString("on('submit.contentifyEditor'", $adapter);
        $this->assertStringContainsString("attribute.name.indexOf('data-se-')", $adapter);
        $this->assertStringContainsString("className.indexOf('se-')", $adapter);
    }
}
