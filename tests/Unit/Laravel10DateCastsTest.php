<?php

namespace Tests\Unit;

use App\Modules\Cups\CupMatch;
use App\Modules\Matches\GameMatch;
use App\Modules\News\News;
use Tests\TestCase;

class Laravel10DateCastsTest extends TestCase
{
    public function testCriticalLegacyDatesUseDatetimeCasts(): void
    {
        $this->assertSame('datetime', (new GameMatch)->getCasts()['played_at']);
        $this->assertSame('datetime', (new CupMatch)->getCasts()['deleted_at']);
        $this->assertSame('datetime', (new News)->getCasts()['published_at']);
    }
}
