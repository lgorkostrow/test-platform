<?php

declare(strict_types=1);

namespace App\Application\Doctrine\DataFixtures;

use App\Domain\Advertisement\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class CategoryFixtures extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $this->createCategories($manager, 10);

        $manager->flush();
    }

    private function createCategories(ObjectManager $manager, int $count = 10): void
    {
        for ($i = 0; $i < $count; $i++) {
            $category = new Category(
                Uuid::uuid4()->toString(),
                $this->faker->words(3, true)
            );

            $manager->persist($category);
        }
    }
}
