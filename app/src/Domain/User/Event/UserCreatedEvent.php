<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

class UserCreatedEvent
{
    /**
     * @var string
     */
    private string $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }
}
