<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\UseCase;

use App\Domain\Advertisement\Dto\AdvertisementDto;
use App\Domain\Common\Message\AsyncMessageInterface;

class CreateAdvertisementCommand implements AsyncMessageInterface
{
    private string $id;

    private AdvertisementDto $dto;

    public function __construct(string $id, AdvertisementDto $dto)
    {
        $this->id = $id;
        $this->dto = $dto;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDto(): AdvertisementDto
    {
        return $this->dto;
    }
}
