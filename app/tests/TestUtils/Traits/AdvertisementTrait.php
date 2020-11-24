<?php

declare(strict_types=1);

namespace App\Tests\TestUtils\Traits;

use App\Domain\Advertisement\Entity\Advertisement;
use App\Domain\Advertisement\ValueObject\AdvertisementDescription;
use App\Domain\Common\State\AbstractState;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

trait AdvertisementTrait
{
    protected ?EntityManagerInterface $entityManager;

    private function findAdvertisementByState(AbstractState $state): Advertisement
    {
        return $this->entityManager->getRepository(Advertisement::class)->findOneBy([
            'state' => $state,
        ]);
    }

    private function getAdvertisementDescription(Advertisement $advertisement): AdvertisementDescription
    {
        $reflectionClass = new \ReflectionClass(Advertisement::class);
        $property = $reflectionClass->getProperty('description');
        $property->setAccessible(true);

        return $property->getValue($advertisement);
    }

    private function getAdvertisementAuthor(Advertisement $advertisement): User
    {
        $reflectionClass = new \ReflectionClass(Advertisement::class);
        $property = $reflectionClass->getProperty('author');
        $property->setAccessible(true);

        return $property->getValue($advertisement);
    }
}
