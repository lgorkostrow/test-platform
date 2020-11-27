<?php

declare(strict_types=1);

namespace App\Tests\TestUtils\Traits;

use Symfony\Component\HttpFoundation\File\UploadedFile;

trait FileTrait
{
    protected function createUploadedFile(string $filePath, string $originalName, string $mimeType): UploadedFile
    {
        return new UploadedFile($filePath, $originalName, $mimeType, null, true);
    }

    protected function createUploadedImageFile(int $width = 200, int $height = 200): UploadedFile
    {
        $filePath = '/tmp/test.png';
        $img = imagecreate($width, $height);
        imagecolorallocate($img, 0, 0, 0);
        imagepng($img, $filePath);
        chmod($filePath, 0777);

        return $this->createUploadedFile($filePath, 'test.png', 'image/png');
    }

    protected function createUploadedTxtFile(): UploadedFile
    {
        $filePath = '/tmp/test1.txt';
        file_put_contents($filePath, 'Some text');
        chmod($filePath, 0777);

        return $this->createUploadedFile($filePath, 'test1.txt', 'text/plain');
    }
}
