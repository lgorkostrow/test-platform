<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\UseCase;

use App\Domain\Advertisement\Factory\AdvertisementFactory;
use App\Domain\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Domain\Common\Message\CommandHandlerInterface;
use App\Domain\File\Manager\FileManager;

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

    /**
     * @var FileManager
     */
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

    public function __invoke(CreateAdvertisementCommand $command)
    {
        $advertisement = $this->factory->create($command->getId(), $command->getDto());
        foreach ($command->getDto()->getAttachments() as $fileDto) {
            $advertisement->addAttachment($this->fileManager->store($fileDto));
        }

        $this->advertisementRepository->save($advertisement);
    }
}
