<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\UseCase;

use App\Domain\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Domain\Common\Message\CommandHandlerInterface;

class PublishAdvertisementCommandHandler implements CommandHandlerInterface
{
    private AdvertisementRepositoryInterface $repository;

    public function __construct(AdvertisementRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(PublishAdvertisementCommand $command): void
    {
        $advertisement = $this->repository->findOrFail($command->getAdvertisementId());
        $advertisement->publish();

        $this->repository->save($advertisement);
    }
}
