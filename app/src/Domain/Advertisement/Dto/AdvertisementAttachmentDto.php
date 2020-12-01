<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Dto;

use App\Domain\File\Dto\FileDto;

class AdvertisementAttachmentDto extends FileDto
{
    /**
     * @var bool
     */
    private bool $featured;

    public function __construct(
        string $id,
        string $storageType,
        string $originalName,
        string $originalExtension,
        string $path,
        string $mimeType,
        int $size,
        bool $featured
    ) {
        parent::__construct($id, $storageType, $originalName, $originalExtension, $path, $mimeType, $size);

        $this->featured = $featured;
    }

    /**
     * @return bool
     */
    public function isFeatured(): bool
    {
        return $this->featured;
    }
}
