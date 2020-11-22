<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Factory;

use App\Domain\Advertisement\Dto\AdvertisementDto;
use App\Domain\Advertisement\Entity\Advertisement;
use App\Domain\Advertisement\Repository\CategoryRepositoryInterface;
use App\Domain\Advertisement\ValueObject\AdvertisementDescription;
use App\Domain\Common\Exception\EntityNotFoundException;
use App\Domain\Common\ValueObject\Price;
use App\Domain\User\Repository\UserRepositoryInterface;

class AdvertisementFactory
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(UserRepositoryInterface $userRepository, CategoryRepositoryInterface $categoryRepository)
    {
        $this->userRepository = $userRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function create(string $id, AdvertisementDto $dto): Advertisement
    {
        if (null === $category = $this->categoryRepository->find($dto->getCategoryId())) {
            throw new EntityNotFoundException();
        }

        if (null === $user = $this->userRepository->find($dto->getUserId())) {
            throw new EntityNotFoundException();
        }

        return new Advertisement(
            $id,
            new AdvertisementDescription($dto->getTitle(), $dto->getDescription()),
            new Price($dto->getPrice(), $dto->getCurrency()),
            $category,
            $user,
        );
    }
}
