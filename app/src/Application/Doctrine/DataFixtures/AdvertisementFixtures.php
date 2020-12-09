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
use App\Domain\Currency\Enum\DefaultCurrencyEnum;
use App\Domain\Currency\ValueObject\Price;
use App\Domain\User\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class AdvertisementFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        foreach ($this->getUsers() as $user) {
            foreach ($categories as $category) {
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
                new Price($this->faker->numberBetween(100, 10000), DefaultCurrencyEnum::LIST[array_rand(DefaultCurrencyEnum::LIST)]),
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
