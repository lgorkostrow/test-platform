<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Event;

use App\Domain\Common\Event\DomainEventInterface;

class AdvertisementCreatedEvent implements DomainEventInterface
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
