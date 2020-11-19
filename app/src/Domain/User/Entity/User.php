<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Common\Entity\Timestampable;
use App\Domain\Common\Entity\TimestampableInterface;
use App\Domain\User\UserInterface;
use App\Domain\User\ValueObject\PersonalData;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\User\Repository\Doctrine\UserRepository")
 */
class User implements UserInterface, TimestampableInterface
{
    use Timestampable;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="guid", unique=true)
     */
    private string $id;

    /**
     * @var PersonalData
     *
     * @ORM\Embedded(class=PersonalData::class, columnPrefix=false)
     */
    private PersonalData $personalData;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $password;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $confirmationToken;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private bool $emailConfirmed;

    public function __construct(string $id, PersonalData $personalData, string $confirmationToken)
    {
        $this->id = $id;
        $this->personalData = $personalData;
        $this->confirmationToken = $confirmationToken;
        $this->emailConfirmed = false;
    }

    public function getRoles()
    {
        return [];
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return User
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->personalData->getEmail();
    }

    public function eraseCredentials() {}

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function verify(): void
    {
        $this->emailConfirmed = true;
        $this->confirmationToken = null;
    }

    /**
     * @return bool
     */
    public function isEmailConfirmed(): bool
    {
        return $this->emailConfirmed;
    }
}
