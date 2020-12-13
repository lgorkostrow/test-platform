<?php

declare(strict_types=1);

namespace App\Domain\File\Factory;

use App\Domain\File\Entity\File;
use App\Domain\File\ValueObject\MetaData;

class FileFactory
{
    public function create(
        string $id,
        string $storageType,
        string $path,
        string $originalFileName,
        float $size,
        string $mimeType
    ): File {
        return new File(
            $id,
            $storageType,
            $path,
            new MetaData($originalFileName, $size / 1000, $mimeType),
        );
    }
}
