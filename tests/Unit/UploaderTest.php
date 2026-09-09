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

    public function test_it_rejects_svg_and_disguised_php_as_images(): void
    {
        foreach ([
            UploadedFile::fake()->createWithContent('vector.svg', '<svg><script>alert(1)</script></svg>'),
            UploadedFile::fake()->createWithContent('photo.jpg', '<?php echo "executed";'),
        ] as $file) {
            Request::swap(\Illuminate\Http\Request::create('/', 'POST', [], [], ['image' => $file]));
            $model = new UploadModelStub($this->uploadDirectory);
            $errors = (new Uploader())->uploadModelFiles($model);
            $this->assertNotEmpty($errors);
            $this->assertNull($model->image);
            $this->assertSame([], array_values(array_diff(scandir($this->uploadDirectory), ['.', '..'])));
        }
    }

    public function test_it_uses_detected_image_type_instead_of_client_suffix(): void
    {
        $path = $this->uploadDirectory.DIRECTORY_SEPARATOR.'source.png';
        file_put_contents($path, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='
        ));
        Request::swap(\Illuminate\Http\Request::create('/', 'POST', [], [], [
            'image' => new UploadedFile($path, 'misleading.gif', 'image/gif', null, true),
        ]));
        $model = new UploadModelStub($this->uploadDirectory);
        $this->assertSame([], (new Uploader())->uploadModelFiles($model));
        $this->assertStringEndsWith('.png', $model->image);
        $this->assertMatchesRegularExpression('/^[a-f0-9]{32}\.png$/', $model->image);
    }

    public function test_it_rejects_executable_generic_download_suffixes(): void
    {
        foreach (['payload.phtml', 'payload.PHP8', 'payload.svg', 'payload.htaccess'] as $name) {
            Request::swap(\Illuminate\Http\Request::create('/', 'POST', [], [], [
                'file' => UploadedFile::fake()->createWithContent($name, 'test'),
            ]));
            $model = new DownloadUploadModelStub($this->uploadDirectory);
            $this->assertNotEmpty((new Uploader())->uploadModelFiles($model));
            $this->assertNull($model->file);
        }
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

class DownloadUploadModelStub extends UploadModelStub
{
    public static $fileHandling = ['file'];
    public $file;
}
