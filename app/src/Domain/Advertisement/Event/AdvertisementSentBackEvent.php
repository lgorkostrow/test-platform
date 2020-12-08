<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Event;

use App\Domain\Common\Event\DomainEventInterface;

class AdvertisementSentBackEvent implements DomainEventInterface
{
    private string $advertisementId;

    private string $authorId;

    private string $reason;

    public function __construct(string $advertisementId, string $authorId, string $reason)
    {
        $this->advertisementId = $advertisementId;
        $this->authorId = $authorId;
        $this->reason = $reason;
    }

    public function getAdvertisementId(): string
    {
        return $this->advertisementId;
    }

    public function getAuthorId(): string
    {
        return $this->authorId;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}
