<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase;

use App\Domain\Common\Message\AsyncMessageInterface;

class VerifyUserEmailCommand implements AsyncMessageInterface
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
