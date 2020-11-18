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
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getBiography(): ?string
    {
        return $this->biography;
    }
}
