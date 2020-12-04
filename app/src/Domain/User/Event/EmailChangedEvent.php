<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\Common\Event\DomainEventInterface;

class EmailChangedEvent implements DomainEventInterface
{
    /**
     * @var string
     */
    private string $userId;

    /**
     * @var string
     */
    private string $newEmail;

    public function __construct(string $userId, string $newEmail)
    {
        $this->userId = $userId;
        $this->newEmail = $newEmail;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getNewEmail(): string
    {
        return $this->newEmail;
    }
}
