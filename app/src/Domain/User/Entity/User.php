<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Common\Entity\Timestampable;
use App\Domain\Common\Entity\TimestampableInterface;
use App\Domain\Common\Event\RaiseEventsInterface;
use App\Domain\Common\Event\RaiseEventsTrait;
use App\Domain\Common\Exception\BusinessException;
use App\Domain\User\Enum\RoleEnum;
use App\Domain\User\Event\EmailChangedEvent;
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
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    private ?string $newEmail;

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

    public static function createUser(string $id, PersonalData $personalData, string $confirmationToken): User
    {
        return new self(
            $id,
            $personalData,
            $confirmationToken,
            [RoleEnum::ROLE_USER],
        );
    }

    public static function createManager(string $id, PersonalData $personalData, string $confirmationToken): User
    {
        return new self(
            $id,
            $personalData,
            $confirmationToken,
            [RoleEnum::ROLE_USER, RoleEnum::ROLE_MANAGER],
        );
    }

    public static function createAdmin(string $id, PersonalData $personalData, string $confirmationToken): User
    {
        return new self(
            $id,
            $personalData,
            $confirmationToken,
            [RoleEnum::ROLE_ADMIN, RoleEnum::ROLE_USER, RoleEnum::ROLE_MANAGER],
        );
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->personalData->getEmail();
    }

    public function eraseCredentials() {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->personalData->getEmail();
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function verify(): void
    {
        $this->emailConfirmed = true;
        $this->confirmationToken = null;
    }

    public function isEmailConfirmed(): bool
    {
        return $this->emailConfirmed;
    }

    public function isManager(): bool
    {
        return in_array(RoleEnum::ROLE_MANAGER, $this->roles, true);
    }

    public function isAdmin(): bool
    {
        return in_array(RoleEnum::ROLE_ADMIN, $this->roles, true);
    }

    public function isManagerOrAdmin(): bool
    {
        return $this->isManager() || $this->isAdmin();
    }

    public function updatePersonalData(PersonalData $personalData): void
    {
        if ($this->personalData->getEmail() !== $personalData->getEmail()) {
            throw new BusinessException('EMAIL CAN\'T BE CHANGED');
        }

        $this->personalData = $personalData;
    }

    public function updateEmail(string $newEmail, string $token): void
    {
        if ($this->personalData->getEmail() === $newEmail) {
            return;
        }

        $this->confirmationToken = $token;
        $this->newEmail = $newEmail;

        $this->raise(new EmailChangedEvent($this->id, $newEmail));
    }

    public function confirmNewEmail(): void
    {
        if (!$this->newEmail) {
            throw new BusinessException('EMPTY_NEW_EMAIL');
        }

        $this->personalData = new PersonalData(
            $this->personalData->getFirstName(),
            $this->personalData->getLastName(),
            $this->newEmail,
            $this->personalData->getBiography(),
        );
        $this->newEmail = null;
        $this->confirmationToken = null;
    }
}
