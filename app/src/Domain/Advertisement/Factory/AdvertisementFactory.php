<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Factory;

use App\Domain\Advertisement\Dto\AdvertisementDto;
use App\Domain\Advertisement\Entity\Advertisement;
use App\Domain\Advertisement\Repository\CategoryRepositoryInterface;
use App\Domain\Advertisement\ValueObject\AdvertisementDescription;
use App\Domain\Common\ValueObject\Price;
use App\Domain\User\Repository\UserRepositoryInterface;

class AdvertisementFactory
{
    private UserRepositoryInterface $userRepository;

    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(UserRepositoryInterface $userRepository, CategoryRepositoryInterface $categoryRepository)
    {
        $this->userRepository = $userRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function create(string $id, AdvertisementDto $dto): Advertisement
    {
        return new Advertisement(
            $id,
            new AdvertisementDescription($dto->getTitle(), $dto->getDescription()),
            new Price($dto->getPrice(), $dto->getCurrency()),
            $this->categoryRepository->findOrFail($dto->getCategoryId()),
            $this->userRepository->findOrFail($dto->getUserId()),
        );
    }
}
