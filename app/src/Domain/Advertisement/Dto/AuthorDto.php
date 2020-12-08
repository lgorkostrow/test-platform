<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Dto;

class AuthorDto
{
    private string $id;

    private string $fullName;

    private string $email;

    public function __construct(string $id, string $fullName, string $email)
    {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->email = $email;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
