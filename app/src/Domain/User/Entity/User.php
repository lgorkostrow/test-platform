<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Common\Entity\Timestampable;
use App\Domain\Common\Entity\TimestampableInterface;
use App\Domain\Common\Event\RaiseEventsInterface;
use App\Domain\Common\Event\RaiseEventsTrait;
use App\Domain\User\Enum\RoleEnum;
use App\Domain\User\Event\UserCreatedEvent;
use App\Domain\User\UserInterface;
use App\Domain\User\ValueObject\PersonalData;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\User\Repository\Doctrine\UserRepository")
 */
class User implements UserInterface, TimestampableInterface, RaiseEventsInterface
{
    use Timestampable;
    use RaiseEventsTrait;

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
     * @var array|string
     *
     * @ORM\Column(type="json")
     */
    private array $roles;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private bool $emailConfirmed;

    private function __construct(string $id, PersonalData $personalData, string $confirmationToken, array $roles)
    {
        $this->id = $id;
        $this->personalData = $personalData;
        $this->confirmationToken = $confirmationToken;
        $this->roles = $roles;
        $this->emailConfirmed = false;

        $this->raise(new UserCreatedEvent($id));
    }

    public static function createUser(string $id, PersonalData $personalData, string $confirmationToken)
    {
        return new self(
            $id,
            $personalData,
            $confirmationToken,
            [RoleEnum::ROLE_USER],
        );
    }

    public static function createAdmin(string $id, PersonalData $personalData, string $confirmationToken)
    {
        return new self(
            $id,
            $personalData,
            $confirmationToken,
            [RoleEnum::ROLE_ADMIN, RoleEnum::ROLE_USER, RoleEnum::ROLE_MANAGER],
        );
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
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

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->personalData->getEmail();
    }

    /**
     * @return string|null
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
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

    /**
     * @return bool
     */
    public function isManager(): bool
    {
        return in_array(RoleEnum::ROLE_MANAGER, $this->roles);
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return in_array(RoleEnum::ROLE_ADMIN, $this->roles);
    }

    /**
     * @return bool
     */
    public function isManagerOrAdmin(): bool
    {
        return $this->isManager() || $this->isAdmin();
    }
}
