<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\Common\Event\DomainEventInterface;

class UserCreatedEvent implements DomainEventInterface
{
    private string $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
