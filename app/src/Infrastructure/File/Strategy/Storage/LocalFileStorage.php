<?php

declare(strict_types=1);

namespace App\Infrastructure\File\Strategy\Storage;

use App\Domain\File\Enum\StorageTypeEnum;
use App\Domain\File\Storage\FileStorageInterface;
use App\Domain\File\Utils\FileUtils;
use RuntimeException;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\File\File;

class LocalFileStorage implements FileStorageInterface
{
    private string $fileDir;

    private string $publicDir;

    private Packages $assets;

    public function __construct(string $fileDir, string $publicDir, Packages $assets)
    {
        $this->fileDir = $fileDir;
        $this->publicDir = $publicDir;
        $this->assets = $assets;
    }

    public function supports(string $storageType): bool
    {
        return StorageTypeEnum::LOCAL === $storageType;
    }

    public function buildFullPath(string $path): string
    {
        return $this->assets->getUrl(
            FileUtils::getRelativePath($this->publicDir, $path)
        );
    }

    public function upload(string $path, string $fileName): string
    {
        $this->checkDir();

        $file = $this->createFile($path);
        $file = $file->move(
            $this->fileDir,
            $fileName
        );

        return $file->getRealPath();
    }

    private function createFile(string $path): File
    {
        return new File($path);
    }

    private function checkDir(): void
    {
        if (!is_dir($this->fileDir) && !mkdir($concurrentDirectory = $this->fileDir) && !is_dir($concurrentDirectory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
    }
}
