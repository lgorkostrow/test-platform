<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Event;

use App\Domain\Common\Event\DomainEventInterface;

class AdvertisementSentBackEvent implements DomainEventInterface
{
    /**
     * @var string
     */
    private string $advertisementId;

    /**
     * @var string
     */
    private string $authorId;

    /**
     * @var string
     */
    private string $reason;

    public function __construct(string $advertisementId, string $authorId, string $reason)
    {
        $this->advertisementId = $advertisementId;
        $this->authorId = $authorId;
        $this->reason = $reason;
    }

    /**
     * @return string
     */
    public function getAdvertisementId(): string
    {
        return $this->advertisementId;
    }

    /**
     * @return string
     */
    public function getAuthorId(): string
    {
        return $this->authorId;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }
}
