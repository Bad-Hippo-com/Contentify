<?php

use Contentify\Uploader;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Request;

require __DIR__.'/../../vendor/autoload.php';

$app = require __DIR__.'/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$root = storage_path('framework/testing/uploader-smoke-'.uniqid('', true));
mkdir($root, 0777, true);

try {
    $makePng = function (string $name) use ($root): UploadedFile {
        $path = $root.DIRECTORY_SEPARATOR.$name;
        file_put_contents(
            $path,
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=')
        );

        return new UploadedFile($path, $name, 'image/png', null, true);
    };

    Request::swap(HttpRequest::create('/', 'POST', [], [], [
        'image' => $makePng('logo.png'),
        'banner' => $makePng('banner.png'),
    ]));

    $first = new UploaderSmokeModel($root);
    $errors = (new Uploader())->uploadModelFiles($first);
    if ($errors || ! $first->image || ! $first->banner || $first->saveCount !== 2) {
        throw new RuntimeException('Logo und Banner wurden nicht gemeinsam verarbeitet.');
    }

    Request::swap(HttpRequest::create('/', 'POST', [], [], [
        'banner' => $makePng('banner-only.png'),
    ]));

    $second = new UploaderSmokeModel($root);
    $errors = (new Uploader())->uploadModelFiles($second);
    if ($errors || $second->image || ! $second->banner || $second->saveCount !== 1) {
        throw new RuntimeException('Das zweite Dateifeld wurde bei leerem ersten Feld nicht verarbeitet.');
    }

    echo "OK: Mehrere Dateifelder und ein einzelnes nachgelagertes Dateifeld wurden verarbeitet.\n";
} finally {
    File::deleteDirectory($root);
}

class UploaderSmokeModel
{
    public static $fileHandling = [
        'image' => ['type' => 'image'],
        'banner' => ['type' => 'image'],
    ];

    public $image;
    public $banner;
    public $saveCount = 0;

    private $root;

    public function __construct(string $root)
    {
        $this->root = rtrim($root, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }

    public function uploadPath(bool $absolute = false): string
    {
        return $this->root;
    }

    public function forceSave(): void
    {
        $this->saveCount++;
    }

    public function delete(): void
    {
    }
}
