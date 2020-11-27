<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\UseCase;

use App\Domain\Common\Message\AsyncMessageInterface;

class SendBackAdvertisementCommand implements AsyncMessageInterface
{
    /**
     * @var string
     */
    private string $advertisementId;

    /**
     * @var string
     */
    private string $reason;

    public function __construct(string $advertisementId, string $reason)
    {
        $this->advertisementId = $advertisementId;
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
    public function getReason(): string
    {
        return $this->reason;
    }
}
