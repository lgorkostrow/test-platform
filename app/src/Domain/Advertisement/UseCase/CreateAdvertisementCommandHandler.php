<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\UseCase;

use App\Domain\Advertisement\Factory\AdvertisementFactory;
use App\Domain\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Domain\Common\Message\CommandHandlerInterface;
use App\Domain\File\Manager\FileManager;

class CreateAdvertisementCommandHandler implements CommandHandlerInterface
{
    private AdvertisementRepositoryInterface $advertisementRepository;

    private AdvertisementFactory $factory;

    private FileManager $fileManager;

    public function __construct(
        AdvertisementRepositoryInterface $advertisementRepository,
        AdvertisementFactory $factory,
        FileManager $fileManager
    ) {
        $this->advertisementRepository = $advertisementRepository;
        $this->factory = $factory;
        $this->fileManager = $fileManager;
    }

    public function __invoke(CreateAdvertisementCommand $command): void
    {
        $advertisement = $this->factory->create($command->getId(), $command->getDto());
        foreach ($command->getDto()->getAttachments() as $fileDto) {
            $advertisement->addAttachment($this->fileManager->store($fileDto), $fileDto->isFeatured());
        }

        $this->advertisementRepository->save($advertisement);
    }
}
