<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Dto;

class AuthorDto
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $fullName;

    /**
     * @var string
     */
    private string $email;

    public function __construct(string $id, string $fullName, string $email)
    {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
