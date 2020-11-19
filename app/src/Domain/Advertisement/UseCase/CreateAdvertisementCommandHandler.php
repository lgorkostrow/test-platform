<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\UseCase;

use App\Domain\Advertisement\Factory\AdvertisementFactory;
use App\Domain\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Domain\Common\Message\CommandHandlerInterface;

class CreateAdvertisementCommandHandler implements CommandHandlerInterface
{
    /**
     * @var AdvertisementRepositoryInterface
     */
    private AdvertisementRepositoryInterface $advertisementRepository;

    /**
     * @var AdvertisementFactory
     */
    private AdvertisementFactory $factory;

    public function __construct(
        AdvertisementRepositoryInterface $advertisementRepository,
        AdvertisementFactory $factory
    ) {
        $this->advertisementRepository = $advertisementRepository;
        $this->factory = $factory;
    }

    public function __invoke(CreateAdvertisementCommand $command)
    {
        $this->advertisementRepository->save(
            $this->factory->create($command->getId(), $command->getDto())
        );
    }
}
