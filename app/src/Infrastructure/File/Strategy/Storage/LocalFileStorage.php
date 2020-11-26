<?php

declare(strict_types=1);

namespace App\Infrastructure\File\Strategy\Storage;

use App\Domain\File\Enum\StorageTypeEnum;
use App\Domain\File\Storage\FileStorageInterface;
use Symfony\Component\HttpFoundation\File\File;

class LocalFileStorage implements FileStorageInterface
{
    /**
     * @var string
     */
    private string $fileDir;

    public function __construct(string $fileDir)
    {
        $this->fileDir = $fileDir;
    }

    public function supports(string $storageType): bool
    {
        return StorageTypeEnum::LOCAL === $storageType;
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
        if (!is_dir($this->fileDir)) {
            mkdir($this->fileDir);
        }
    }
}
