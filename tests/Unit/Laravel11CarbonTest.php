<?php

namespace Tests\Unit;

use Contentify\Carbon;
use Tests\TestCase;

class Laravel11CarbonTest extends TestCase
{
    public function testContentifyDateMethodsUseTheTranslatedDateFormat(): void
    {
        $date = Carbon::create(2026, 9, 7, 10, 20, 30);
        $format = trans('app.date_format');

        $this->assertSame($date->format($format), $date->date());
        $this->assertSame($date->format($format).' 10:20:30', $date->dateTime());
    }

    public function testCarbonThreeDifferenceCanBeComparedWithoutDirection(): void
    {
        $created = Carbon::create(2026, 9, 7, 10, 0, 0);
        $updated = Carbon::create(2026, 9, 7, 10, 5, 0);

        $this->assertSame(5.0, abs($updated->diffInMinutes($created)));
    }
}
