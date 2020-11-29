<?php

declare(strict_types=1);

namespace App\Application\Doctrine\DataFixtures;

use App\Domain\Advertisement\Entity\Advertisement;
use App\Domain\Advertisement\Entity\Category;
use App\Domain\Advertisement\State\Advertisement\ArchivedState;
use App\Domain\Advertisement\State\Advertisement\DraftState;
use App\Domain\Advertisement\State\Advertisement\OnReviewState;
use App\Domain\Advertisement\State\Advertisement\PublishedState;
use App\Domain\Advertisement\ValueObject\AdvertisementDescription;
use App\Domain\Common\Enum\CurrencyEnum;
use App\Domain\Common\ValueObject\Price;
use App\Domain\User\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class AdvertisementFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->entityManager->getRepository(Category::class)->findAll() as $category) {
            foreach ($this->getUsers() as $user) {
                $this->createForCategory($manager, $category, $user);
                $this->createForCategory($manager, $category, $user, OnReviewState::NAME);
                $this->createForCategory($manager, $category, $user, PublishedState::NAME);
                $this->createForCategory($manager, $category, $user, ArchivedState::NAME);
            }
        }

        $manager->flush();
    }

    private function createForCategory(
        ObjectManager $manager,
        Category $category,
        User $author,
        string $status = DraftState::NAME,
        int $count = 5
    ): void {
        for ($i = 0; $i < $count; $i++) {
            $advertisement = new Advertisement(
                Uuid::uuid4()->toString(),
                new AdvertisementDescription($this->faker->words(3, true), $this->faker->text),
                new Price($this->faker->numberBetween(100, 10000), CurrencyEnum::VALID_CHOICES[array_rand(CurrencyEnum::VALID_CHOICES)]),
                $category,
                $author,
            );

            switch ($status) {
                case DraftState::NAME:
                    break;
                case OnReviewState::NAME:
                    $advertisement->sendToReview();
                    break;
                case PublishedState::NAME:
                    $advertisement->sendToReview();
                    $advertisement->publish();
                    break;
                case ArchivedState::NAME:
                    $advertisement->sendToReview();
                    $advertisement->publish();
                    $advertisement->archive();
                    break;
            }

            $manager->persist($advertisement);
        }
    }
}
