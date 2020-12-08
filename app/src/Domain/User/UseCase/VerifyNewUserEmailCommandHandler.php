<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase;

use App\Domain\Common\Exception\BusinessException;
use App\Domain\Common\Message\CommandHandlerInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

class VerifyNewUserEmailCommandHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(VerifyNewUserEmailCommand $command): void
    {
        $user = $this->repository->findOrFail($command->getUserId());
        if ($user->getConfirmationToken() !== $command->getToken()) {
            throw new BusinessException('INVALID_TOKEN');
        }

        $user->confirmNewEmail();

        $this->repository->save($user);
    }
}
