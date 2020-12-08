<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class PersonalData
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $lastName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $email;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $biography;

    public function __construct(string $firstName, string $lastName, string $email, ?string $biography = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->biography = $biography;
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

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }
}
