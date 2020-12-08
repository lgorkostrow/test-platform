<?php

declare(strict_types=1);

namespace App\Domain\User\Dto;

class UserDto
{
    private string $email;

    private string $password;

    private string $firstName;

    private string $lastName;

    private ?string $biography;

    public function __construct(string $email, string $password, string $firstName, string $lastName, ?string $biography)
    {
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->biography = $biography;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }
}
