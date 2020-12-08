<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase;

use App\Domain\Common\Message\AsyncMessageInterface;

class UpdatePersonalDataCommand implements AsyncMessageInterface
{
    private string $userId;

    private string $firstName;

    private string $lastName;

    private string $email;

    private ?string $biography;

    public function __construct(string $userId, string $firstName, string $lastName, string $email, ?string $biography)
    {
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->biography = $biography;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }
}
