<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\UseCase;

use App\Domain\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Domain\Common\Exception\EntityNotFoundException;
use App\Domain\Common\Message\CommandHandlerInterface;

class SendAdvertisementToReviewCommandHandler implements CommandHandlerInterface
{
    /**
     * @var AdvertisementRepositoryInterface
     */
    private AdvertisementRepositoryInterface $repository;

    public function __construct(AdvertisementRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SendAdvertisementToReviewCommand $command)
    {
        if (null === $advertisement = $this->repository->find($command->getAdvertisementId())) {
            throw new EntityNotFoundException();
        }

        $advertisement->sendToReview();

        $this->repository->save($advertisement);
    }
}
