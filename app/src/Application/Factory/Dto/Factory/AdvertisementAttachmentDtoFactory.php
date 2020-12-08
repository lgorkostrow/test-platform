<?php

declare(strict_types=1);

namespace App\Application\Factory\Dto\Factory;

use App\Application\Http\Request\Advertisement\AttachmentDto;
use App\Domain\Advertisement\Dto\AdvertisementAttachmentDto;

class AdvertisementAttachmentDtoFactory
{
    private string $storageType;

    public function __construct(string $storageType)
    {
        $this->storageType = $storageType;
    }

    public function create(string $id, AttachmentDto $dto): AdvertisementAttachmentDto
    {
        return new AdvertisementAttachmentDto(
            $id,
            $this->storageType,
            $dto->file->getClientOriginalName(),
            $dto->file->getClientOriginalExtension(),
            $dto->file->getRealPath(),
            $dto->file->getMimeType(),
            $dto->file->getSize(),
            $dto->featured,
        );
    }
}
