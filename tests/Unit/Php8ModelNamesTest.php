<?php

namespace Tests\Unit;

use App\Modules\Cups\CupMatch;
use App\Modules\Games\Game;
use App\Modules\Matches\GameMatch;
use App\Modules\Matches\MatchScore;
use App\Modules\Opponents\Opponent;
use App\Modules\Teams\Team;
use App\Modules\Tournaments\Tournament;
use Tests\TestCase;

class Php8ModelNamesTest extends TestCase
{
    public function test_renamed_models_keep_their_historical_tables(): void
    {
        $this->assertSame('matches', (new GameMatch())->getTable());
        $this->assertSame('cups_matches', (new CupMatch())->getTable());
    }

    public function test_relations_reference_the_php_8_compatible_models(): void
    {
        $this->assertSame(GameMatch::class, MatchScore::$relationsData['match'][1]);
        $this->assertSame(GameMatch::class, Game::$relationsData['matches'][1]);
        $this->assertSame(GameMatch::class, Tournament::$relationsData['matches'][1]);
        $this->assertSame(GameMatch::class, Team::$relationsData['matches'][1]);
        $this->assertSame(GameMatch::class, Opponent::$relationsData['matches'][1]);

        $this->assertTrue(class_exists(CupMatch::class));
    }
}
