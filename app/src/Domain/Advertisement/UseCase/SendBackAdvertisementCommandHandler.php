<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\UseCase;

use App\Domain\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Domain\Common\Message\CommandHandlerInterface;

class SendBackAdvertisementCommandHandler implements CommandHandlerInterface
{
    private AdvertisementRepositoryInterface $repository;

    public function __construct(AdvertisementRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SendBackAdvertisementCommand $command): void
    {
        $advertisement = $this->repository->findOrFail($command->getAdvertisementId());
        $advertisement->sendBack($command->getReason());

        $this->repository->save($advertisement);
    }
}
