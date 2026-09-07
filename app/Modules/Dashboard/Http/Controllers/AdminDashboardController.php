<?php

namespace App\Modules\Dashboard\Http\Controllers;

use BackController;
use Cache;
use Config;
use Contentify\DashboardFeed;
use Contentify\DiskSpace;
use HTML;
use Log;
use View;

class AdminDashboardController extends BackController
{

    const FEEDS = [
        [
            'key'         => 'bad-hippo',
            'name'        => 'Bad Hippo 3.3-dev',
            'url'         => 'https://raw.githubusercontent.com/Bad-Hippo-com/Contentify/main/public/share/feeds/cms.json?v=0.13.0',
            'project_url' => 'https://github.com/Bad-Hippo-com/Contentify',
        ],
        [
            'key'         => 'contentify-original',
            'name'        => 'Contentify Original',
            'url'         => 'https://www.contentify.org/share/feeds/cms.json',
            'project_url' => 'https://github.com/Contentify/Contentify',
        ],
    ];

    /**
     * Show a warning when there is less free disk space than defined in this constant
     */
    const MIN_FREE_DISK_SPACE = 100 /* MB */ * 1024 /* KB */ * 1024 /* B */;

    protected $icon = 'home';

    public function getIndex()
    {
        $feed = $this->feed();

        if (Config::get('app.env') == 'production' and Config::get('app.debug') and $_SERVER['HTTP_HOST'] != 'localhost') {
            $this->alertWarning(trans('app.debug_warning').' '.HTML::link(
                'https://github.com/Contentify/Contentify/wiki/FAQ#how-can-i-disable-the-debug-mode',
                trans('app.read_more')
            ));
        }

        $freeBytes = DiskSpace::freeBytes(base_path());
        if ($freeBytes !== null and $freeBytes < self::MIN_FREE_DISK_SPACE) {
            $freeSpace = round($freeBytes / 1024 / 1024).'M';
            $this->alertWarning(trans('app.space_warning', [$freeSpace]));
        }

        $this->pageView('dashboard::admin_index', compact('feed'));
    }

    /**
     * Receive feed, render feed view, cache it and return the HTML code.
     *
     * @return string|null
     */
    public function feed()
    {
        $feeds = [];

        foreach (self::FEEDS as $definition) {
            $feeds[] = [
                'name'        => $definition['name'],
                'project_url' => $definition['project_url'],
                'messages'    => $this->feedMessages($definition),
            ];
        }

        return View::make('dashboard::feed', compact('feeds'))->render();
    }

    /**
     * Receive and cache one feed independently from all other feed sources.
     *
     * @param array $definition
     * @return array
     */
    protected function feedMessages(array $definition): array
    {
        $key = 'dashboard::feedMessages::v3::'.$definition['key'];

        if (Cache::has($key)) {
            return Cache::get($key);
        }

        // File::get() cannot access remote targets, so use the PHP function.
        $content = @file_get_contents($definition['url']);

        if ($content === false) {
            Cache::put($key, [], 10 * 60);
            Log::warning("Failed to fetch dashboard message feed '".$definition['url']."'");

            return [];
        }

        $messages = DashboardFeed::decode($content, $definition['project_url']);

        if ($messages === null) {
            Cache::put($key, [], 10 * 60);
            Log::warning("Invalid dashboard message feed '".$definition['url']."'");

            return [];
        }

        Cache::put($key, $messages, 60 * 6 * 60);

        return $messages;
    }
}
