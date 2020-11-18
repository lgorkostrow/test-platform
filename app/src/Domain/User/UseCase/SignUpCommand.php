<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase;

use App\Domain\Common\Message\AsyncMessageInterface;
use App\Domain\Common\Message\ValidatableCommandInterface;
use App\Domain\User\Dto\UserDto;

class SignUpCommand implements AsyncMessageInterface, ValidatableCommandInterface
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var UserDto
     */
    private UserDto $dto;

    public function __construct(string $id, UserDto $dto)
    {
        $this->id = $id;
        $this->dto = $dto;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return UserDto
     */
    public function getDto(): UserDto
    {
        return $this->dto;
    }

    public function getDataToValidate(): object
    {
        return $this->dto;
    }
}
