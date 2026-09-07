<?php

namespace App\Modules\Matches\Http\Controllers;

use App\Modules\Matches\GameMatch;
use View;
use Widget;

class FeaturedMatchWidget extends Widget
{

    public function render(array $parameters = []) : string
    {
        $match = GameMatch::orderBy('played_at', 'DESC')->whereFeatured(true)->where('state', '!=', GameMatch::STATE_HIDDEN)->first();

        if ($match) {
            return View::make('matches::featured_widget', compact('match'))->render();
        }

        return '';
    }
}
