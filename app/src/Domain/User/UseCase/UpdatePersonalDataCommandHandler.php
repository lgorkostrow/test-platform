<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase;

use App\Domain\Common\Message\CommandHandlerInterface;
use App\Domain\Common\Service\TokenGeneratorInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\ValueObject\PersonalData;

class UpdatePersonalDataCommandHandler implements CommandHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $repository;

    /**
     * @var TokenGeneratorInterface
     */
    private TokenGeneratorInterface $generator;

    public function __construct(UserRepositoryInterface $repository, TokenGeneratorInterface $generator)
    {
        $this->repository = $repository;
        $this->generator = $generator;
    }

    public function __invoke(UpdatePersonalDataCommand $command)
    {
        $user = $this->repository->findOrFail($command->getUserId());

        if ($user->getEmail() !== $command->getEmail()) {
            $user->updateEmail($command->getEmail(), $this->generator->generateToken());
        }

        $user->updatePersonalData(
            new PersonalData(
                $command->getFirstName(),
                $command->getLastName(),
                $user->getEmail(),
                $command->getBiography()
            )
        );

        $this->repository->save($user);
    }
}
