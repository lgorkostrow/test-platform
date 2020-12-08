<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\Common\Event\DomainEventInterface;

class EmailChangedEvent implements DomainEventInterface
{
    private string $userId;

    private string $newEmail;

    public function __construct(string $userId, string $newEmail)
    {
        $this->userId = $userId;
        $this->newEmail = $newEmail;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getNewEmail(): string
    {
        return $this->newEmail;
    }
}
