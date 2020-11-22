<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Domain\Common\Service\TokenGeneratorInterface;
use App\Domain\User\Dto\UserDto;
use App\Domain\User\Entity\User;
use App\Domain\User\Service\PasswordEncoderInterface;
use App\Domain\User\ValueObject\PersonalData;

class UserFactory
{
    /**
     * @var TokenGeneratorInterface
     */
    private TokenGeneratorInterface $generator;

    /**
     * @var PasswordEncoderInterface
     */
    private PasswordEncoderInterface $encoder;

    public function __construct(TokenGeneratorInterface $generator, PasswordEncoderInterface $encoder)
    {
        $this->generator = $generator;
        $this->encoder = $encoder;
    }

    /**
     * @param string $id
     * @param UserDto $dto
     * @return User
     */
    public function create(string $id, UserDto $dto): User
    {
        $user = User::createUser(
            $id,
            new PersonalData(
                $dto->getFirstName(),
                $dto->getLastName(),
                $dto->getEmail(),
                $dto->getBiography(),
            ),
            $this->generator->generateToken(),
        );

        $user->setPassword(
            $this->encoder->encodePassword($user, $dto->getPassword())
        );

        return $user;
    }
}
