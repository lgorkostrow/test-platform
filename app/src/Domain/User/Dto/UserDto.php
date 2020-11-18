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

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string|null
     */
    public function getBiography(): ?string
    {
        return $this->biography;
    }
}
