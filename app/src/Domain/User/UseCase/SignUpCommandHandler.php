<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase;

use App\Domain\Common\Message\CommandHandlerInterface;
use App\Domain\User\Exception\UserWithEmailAlreadyExistsException;
use App\Domain\User\Factory\UserFactory;
use App\Domain\User\Repository\UserRepositoryInterface;

class SignUpCommandHandler implements CommandHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $repository;

    /**
     * @var UserFactory
     */
    private UserFactory $factory;

    public function __construct(UserRepositoryInterface $repository, UserFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    public function __invoke(SignUpCommand $command)
    {
        if (null !== $user = $this->repository->findByEmail($command->getDto()->getEmail())) {
            throw new UserWithEmailAlreadyExistsException();
        }

        $this->repository->save(
            $this->factory->create($command->getId(), $command->getDto())
        );
    }
}
