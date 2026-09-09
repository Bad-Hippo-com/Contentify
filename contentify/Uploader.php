<?php

namespace Contentify;

use File;
use Illuminate\Http\UploadedFile;
use InterImage;
use Request;
use Throwable;

/**
 * This class is the centralized place to handle file uploads from the browser
 */
class Uploader
{
    /**
     * Array that contains all allowed file extensions for image file uploads
     */
    const ALLOWED_IMG_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

    const IMAGE_MIME_EXTENSIONS = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/bmp' => 'bmp',
        'image/x-ms-bmp' => 'bmp',
        'image/webp' => 'webp',
    ];

    /**
     * Array with "evil" file extensions - files with these extensions are not allowed to be uploaded
     */
    const FORBIDDEN_FILE_EXTENSIONS = [
        'php', 'php3', 'php4', 'php5', 'php7', 'php8', 'phtml', 'pht', 'phar',
        'cgi', 'pl', 'py', 'rb', 'sh', 'bash', 'zsh', 'fish', 'htaccess', 'htpasswd',
        'shtml', 'shtm', 'xhtml', 'svg', 'svgz', 'xml', 'xsl', 'xslt', 'html', 'htm',
        'js', 'mjs', 'cjs', 'wasm', 'exe', 'dll', 'com', 'bat', 'cmd', 'ps1', 'msi',
        'jar', 'app', 'deb', 'rpm', 'scr', 'vbs', 'vbe', 'wsf', 'wsh', 'hta', 'lnk',
    ];

    const FORBIDDEN_FILE_MIMES = [
        'application/x-httpd-php', 'application/x-php', 'text/x-php',
        'text/html', 'image/svg+xml', 'application/xhtml+xml',
        'application/x-sh', 'application/x-executable', 'application/x-dosexec',
    ];

    /**
     * Upload files (and images) for a given model.
     * The model must support this.
     * Returns an array with errors.
     *
     * @param object $model The instance of the model the client wants to upload files for
     * @param bool $modelIsNew True if an existing model is being edited
     * @return array
     */
    public function uploadModelFiles($model, $modelIsNew = true) : array
    {
        $modelClass = get_class($model);

        if (isset($modelClass::$fileHandling) and sizeof($modelClass::$fileHandling) > 0) {
            foreach ($modelClass::$fileHandling as $fieldName => $fieldInfo) {
                if (! is_array($fieldInfo)) {
                    $fieldName = $fieldInfo;
                    $fieldInfo = ['type' => 'file'];
                }

                if (Request::hasFile($fieldName)) {
                    $file       = Request::file($fieldName);
                    $isImage = strtolower($fieldInfo['type']) === 'image';
                    [$error, $extension] = $this->validateUploadedFile($file, $isImage);

                    if ($error !== false) {
                        // Only a newly created invalid record may be discarded.
                        // A rejected replacement must never delete existing content.
                        if ($modelIsNew) {
                            $model->delete();
                        }
                        return [$error];
                    }

                    $filePath           = $model->uploadPath(true);
                    $oldFilename        = $modelIsNew ? '' : basename((string) $model->$fieldName);
                    $filename           = $this->generateFilename($filePath, $extension);

                    try {
                        $file->move($filePath, $filename);

                    /*
                     * Create thumbnails for images
                     */
                    if ($isImage && isset($fieldInfo['thumbnails'])) {
                        $thumbnails = $fieldInfo['thumbnails'];

                        // Ensure $thumbnails is an array:
                        if (! is_array($thumbnails)) {
                            $thumbnails = compact('thumbnails'); // Ensure $thumbnails is an array
                        }

                        foreach ($thumbnails as $thumbnail) {
                            if (! File::isDirectory($filePath.$thumbnail)) {
                                File::makeDirectory($filePath.$thumbnail, 0750, true);
                            }
                            InterImage::make($filePath.'/'.$filename)
                                ->resize($thumbnail, $thumbnail, function ($constraint) {
                                    /** @var \Intervention\Image\Constraint $constraint */
                                    $constraint->aspectRatio();
                                })->save($filePath.$thumbnail.'/'.$filename);
                        }
                    }

                        $model->$fieldName = $filename;
                        $model->forceSave();
                    } catch (Throwable $exception) {
                        $this->deleteStoredFile($filePath, $filename, $fieldInfo);
                        throw $exception;
                    }

                    // Replacement is atomic from the model's point of view: only after
                    // the new file and thumbnails exist and the model was saved do we
                    // remove the old assets.
                    if ($oldFilename && $oldFilename !== $filename) {
                        $this->deleteStoredFile($filePath, $oldFilename, $fieldInfo);
                    }
                } else {
                    if ($modelIsNew) {
                        // Ignore missing files
                    } else {
                         // We use the filename '.' to signalize we want to delete the file.
                        // (A file cannot be named "." in Linux.)
                        if (Request::get($fieldName) == '.') {
                            $oldFile = $model->uploadPath(true).basename((string) $model->$fieldName);
                            if (File::isFile($oldFile)) {
                                File::delete($oldFile);
                            }
                            $model->$fieldName  = '';
                            $model->forceSave(); // Save model again, without validation
                        }
                    }
                }

            }
        }

        return [];
    }

    /**
     * Deletes all files related to a given model
     *
     * @param object $model
     * @return void
     */
    public function deleteModelFiles($model)
    {
        $modelClass = get_class($model);

        if ((! method_exists($modelClass, 'trashed') or ! $model->trashed())
            and isset($modelClass::$fileHandling) and sizeof($modelClass::$fileHandling) > 0) {
            $filePath = $model->uploadPath(true);

            foreach ($modelClass::$fileHandling as $fieldName => $fieldInfo) {
                if (! is_array($fieldInfo)) {
                    $fieldName = $fieldInfo;
                    $fieldInfo = ['type' => 'file'];
                }

                $this->deleteStoredFile($filePath, basename((string) $model->$fieldName), $fieldInfo);

            }
        }
    }

    /**
     * Generates a filename for the new uploaded file.
     * The filename will be randomized (via hashing) and unique.
     * To verify its uniqueness the path and extension have to be passed.
     *
     * @param string $filePath      Directory where the file will be moved to
     * @param string $fileExtension Desired extension of the file
     * @return string
     */
    public function generateFilename(string $filePath, string $fileExtension = '') : string
    {
        do {
            $filename = bin2hex(random_bytes(16));
            if ($fileExtension !== '') {
                $filename .= '.'.strtolower($fileExtension);
            }
        } while (file_exists(rtrim($filePath, '/\\').DIRECTORY_SEPARATOR.$filename));

        return $filename;
    }

    public function validateUploadedFile(UploadedFile $file, bool $isImage = false): array
    {
        $extension = strtolower((string) $file->getClientOriginalExtension());
        $mime = strtolower((string) $file->getMimeType());

        if (! $file->isValid() || $extension === '' || ! preg_match('/^[a-z0-9]+$/', $extension)) {
            return [trans($isImage ? 'app.invalid_image' : 'app.bad_extension', [$extension]), ''];
        }

        if ($isImage) {
            $imageData = @getimagesize($file->getRealPath());
            if (! isset(self::IMAGE_MIME_EXTENSIONS[$mime]) || ! isset($imageData['mime']) ||
                strtolower($imageData['mime']) !== $mime) {
                return [trans('app.invalid_image'), ''];
            }
            return [false, self::IMAGE_MIME_EXTENSIONS[$mime]];
        }

        if (in_array($extension, self::FORBIDDEN_FILE_EXTENSIONS, true) ||
            in_array($mime, self::FORBIDDEN_FILE_MIMES, true)) {
            return [trans('app.bad_extension', [$extension]), ''];
        }

        return [false, $extension];
    }

    protected function deleteStoredFile(string $filePath, string $filename, array $fieldInfo): void
    {
        if ($filename === '' || $filename === '.' || basename($filename) !== $filename) {
            return;
        }
        File::delete(rtrim($filePath, '/\\').DIRECTORY_SEPARATOR.$filename);
        if (strtolower($fieldInfo['type']) === 'image' && isset($fieldInfo['thumbnails'])) {
            $thumbnails = is_array($fieldInfo['thumbnails']) ? $fieldInfo['thumbnails'] : [$fieldInfo['thumbnails']];
            foreach ($thumbnails as $thumbnail) {
                File::delete(rtrim($filePath, '/\\').DIRECTORY_SEPARATOR.$thumbnail.DIRECTORY_SEPARATOR.$filename);
            }
        }
    }
}
