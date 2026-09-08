<?php

namespace Contentify\Commands;

use Config;
use HTML;
use File;
use Illuminate\Console\Command;
use Less_Parser;

class LessCompileCommand extends Command
{

    /**
     * Name of the event that is fired after the website settings have been updated
     */
    const EVENT_NAME_LESS_COMPILED = 'contentify.lessCompileCommand.lessCompiled';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'less:compile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Compile the theme's frontend and the backend LESS files to CSS files";

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Compiling LESS files...');

        $theme = Config::get('app.theme');

        // Filenames of the source files (with path and with ".less" as extension!)
        $lessFiles = [
            Config::get('modules.path').'/'.$theme.'/Resources/Assets/less/frontend.less',
            resource_path('assets/less/backend.less'),
        ];

        foreach ($lessFiles as $sourceFilename) {
            $this->compileLessFile($sourceFilename);
        }

        event(self::EVENT_NAME_LESS_COMPILED, [$lessFiles]);

        HTML::refreshAssetPaths();

        return 0;
    }

    /**
     * Compiles a LESS file to a CSS file.
     * If debug mode is NOT active, also compresses the output CSS file.
     *
     * @param string $sourceFilename
     * @return void
     * @throws \Exception
     */
    protected function compileLessFile(string $sourceFilename)
    {
        $sourceFileTitle = basename($sourceFilename, '.less');
        $target = public_path('css/'.$sourceFileTitle.'.css');

        // An explicit rebuild must also include changed imports and admin LESS.
        $parser = new Less_Parser(['compress' => ! Config::get('app.debug')]);
        $parser->parseFile($sourceFilename);
        File::replace($target, $parser->getCss());
        $this->info('CSS kompiliert: '.$sourceFilename.' -> '.$target);
    }

}
