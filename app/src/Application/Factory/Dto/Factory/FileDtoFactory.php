<?php

declare(strict_types=1);

namespace App\Application\Factory\Dto\Factory;

use App\Domain\File\Dto\FileDto;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileDtoFactory
{
    /**
     * @var string
     */
    private string $storageType;

    public function __construct(string $storageType)
    {
        $this->storageType = $storageType;
    }

    public function createFromUploadedFile(string $id, UploadedFile $file): FileDto
    {
        return new FileDto(
            $id,
            $this->storageType,
            $file->getClientOriginalName(),
            $file->getRealPath(),
            $file->getMimeType(),
            $file->getSize(),
        );
    }
}
