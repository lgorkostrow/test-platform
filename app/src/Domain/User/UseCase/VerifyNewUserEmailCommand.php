<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase;

use App\Domain\Common\Message\SyncMessageInterface;

class VerifyNewUserEmailCommand implements SyncMessageInterface
{
    /**
     * @var string
     */
    private string $userId;

    /**
     * @var string
     */
    private string $token;

    public function __construct(string $userId, string $token)
    {
        $this->userId = $userId;
        $this->token = $token;
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
    public function getToken(): string
    {
        return $this->token;
    }
}
