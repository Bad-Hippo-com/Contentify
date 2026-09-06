<?php

namespace Tests\Unit;

use Contentify\Translator;
use Illuminate\Translation\ArrayLoader;
use PHPUnit\Framework\TestCase;

class TranslatorTest extends TestCase
{
    public function test_longer_placeholder_names_are_replaced_first(): void
    {
        $translator = new Translator(new ArrayLoader(), 'de');
        $translator->addLines([
            'messages.example' => ':name_lang vor :name',
        ], 'de');

        $this->assertSame('Langwert vor Kurz', $translator->get('messages.example', [
            'name' => 'Kurz',
            'name_lang' => 'Langwert',
        ]));
    }
}
