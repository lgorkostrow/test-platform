<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Event;

class AdvertisementCreatedEvent
{
    /**
     * @var string
     */
    private string $advertisementId;

    public function __construct($advertisementId)
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
