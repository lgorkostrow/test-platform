<?php

declare(strict_types=1);

namespace App\Tests\TestUtils\Traits;

use App\Domain\Advertisement\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

trait CategoryTrait
{
    protected ?EntityManagerInterface $entityManager;

    private function findRandomCategory(): Category
    {
        return $this->entityManager->getRepository(Category::class)->findOneBy([]);
    }
}
