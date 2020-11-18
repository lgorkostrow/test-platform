<?php

namespace App\Domain\User\UseCase;

use App\Domain\Common\Message\CommandHandlerInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

class VerifyUserEmailCommandHandler implements CommandHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(VerifyUserEmailCommand $command)
    {
        $user = $this->repository->find($command->getUserId());
        if ($user->isEmailConfirmed()) {
            return;
        }

        $user->verify();

        $this->repository->save($user);
    }
}
