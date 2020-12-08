<?php

declare(strict_types=1);

namespace App\Domain\File\Manager;

use App\Domain\File\Dto\FileDto;
use App\Domain\File\Entity\File;
use App\Domain\File\Factory\FileFactory;
use App\Domain\File\Storage\FileStorageInterface;
use App\Domain\File\Utils\FileUtils;
use InvalidArgumentException;

class FileManager
{
    /**
     * @var iterable|FileStorageInterface[]
     */
    private iterable $strategies;

    private FileFactory $factory;

    public function __construct(iterable $strategies, FileFactory $factory)
    {
        $this->strategies = $strategies;
        $this->factory = $factory;
    }

    public function store(FileDto $dto): File
    {
        $storage = $this->getStorage($dto->getStorageType());
        $fileName = FileUtils::generateFileName($dto->getOriginalName(), $dto->getOriginalExtension());

        return $this->factory->create(
            $dto->getId(),
            $storage->upload($dto->getPath(), $fileName),
            $fileName,
            $dto->getSize(),
            $dto->getMimeType(),
        );
    }

    /**
     * @param array|FileDto[] $files
     * @return array|File[]
     */
    public function storeMultiple(array $files): array
    {
        $result = [];
        foreach ($files as $dto) {
            $result[] = $this->store($dto);
        }

        return $result;
    }

    private function getStorage(string $storageType): FileStorageInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($storageType)) {
                return $strategy;
            }
        }

        throw new InvalidArgumentException('INVALID_STORAGE_TYPE');
    }
}
