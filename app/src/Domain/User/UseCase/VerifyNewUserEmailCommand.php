<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase;

use App\Domain\Common\Message\SyncMessageInterface;

class VerifyNewUserEmailCommand implements SyncMessageInterface
{
    private string $userId;

    private string $token;

    public function __construct(string $userId, string $token)
    {
        $this->userId = $userId;
        $this->token = $token;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
