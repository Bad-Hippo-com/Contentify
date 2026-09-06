<?php

namespace Tests\Unit;

use Contentify\Uploader;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Request;
use Tests\TestCase;

class UploaderTest extends TestCase
{
    /** @var string */
    private $uploadDirectory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uploadDirectory = storage_path('framework/testing/uploader-'.uniqid('', true));
        mkdir($this->uploadDirectory, 0777, true);
    }

    protected function tearDown(): void
    {
        if (is_dir($this->uploadDirectory)) {
            \File::deleteDirectory($this->uploadDirectory);
        }

        parent::tearDown();
    }

    public function test_it_processes_all_configured_file_fields(): void
    {
        $request = \Illuminate\Http\Request::create('/', 'POST', [], [], [
            'image' => UploadedFile::fake()->image('logo.png'),
            'banner' => UploadedFile::fake()->image('banner.png'),
        ]);
        Request::swap($request);

        $model = new UploadModelStub($this->uploadDirectory);
        $errors = (new Uploader())->uploadModelFiles($model);

        $this->assertSame([], $errors);
        $this->assertNotEmpty($model->image);
        $this->assertNotEmpty($model->banner);
        $this->assertFileExists($this->uploadDirectory.DIRECTORY_SEPARATOR.$model->image);
        $this->assertFileExists($this->uploadDirectory.DIRECTORY_SEPARATOR.$model->banner);
        $this->assertSame(2, $model->saveCount);
    }

    public function test_it_reaches_a_later_file_field_when_the_first_one_is_empty(): void
    {
        $request = \Illuminate\Http\Request::create('/', 'POST', [], [], [
            'banner' => UploadedFile::fake()->image('banner.png'),
        ]);
        Request::swap($request);

        $model = new UploadModelStub($this->uploadDirectory);
        $errors = (new Uploader())->uploadModelFiles($model);

        $this->assertSame([], $errors);
        $this->assertNull($model->image);
        $this->assertNotEmpty($model->banner);
        $this->assertFileExists($this->uploadDirectory.DIRECTORY_SEPARATOR.$model->banner);
        $this->assertSame(1, $model->saveCount);
    }
}

class UploadModelStub
{
    public static $fileHandling = [
        'image' => ['type' => 'image'],
        'banner' => ['type' => 'image'],
    ];

    public $image;
    public $banner;
    public $saveCount = 0;

    /** @var string */
    private $uploadDirectory;

    public function __construct(string $uploadDirectory)
    {
        $this->uploadDirectory = rtrim($uploadDirectory, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }

    public function uploadPath(bool $absolute = false): string
    {
        return $this->uploadDirectory;
    }

    public function forceSave(): void
    {
        $this->saveCount++;
    }

    public function delete(): void
    {
    }
}
