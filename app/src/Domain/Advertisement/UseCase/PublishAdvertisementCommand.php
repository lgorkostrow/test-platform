<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\UseCase;

use App\Domain\Common\Message\AsyncMessageInterface;

class PublishAdvertisementCommand implements AsyncMessageInterface
{
    /**
     * @var string
     */
    private string $advertisementId;

    public function __construct(string $advertisementId)
    {
        $this->advertisementId = $advertisementId;
    }

    /**
     * @return string
     */
    public function getAdvertisementId(): string
    {
        return $this->advertisementId;
    }
}
