<?php

declare(strict_types=1);

namespace App\Application\Doctrine\DataFixtures;

use App\Domain\User\Entity\User;
use App\Domain\User\Enum\RoleEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;

abstract class AbstractFixture extends Fixture
{
    /**
     * @var Generator
     */
    protected Generator $faker;

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->faker = Factory::create();
        $this->entityManager = $entityManager;
    }

    /**
     * @return array|User[]
     */
    protected function getUsers(): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        return $qb
            ->select('u')
            ->from(User::class, 'u')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->notLike('u.roles', ':adminRole'),
                    $qb->expr()->notLike('u.roles', ':managerRole'),
                )
            )
            ->andWhere('u.emailConfirmed = true')
            ->setParameters([
                'adminRole' => '%' . RoleEnum::ROLE_ADMIN . '%',
                'managerRole' => '%' . RoleEnum::ROLE_MANAGER . '%',
            ])
            ->getQuery()
            ->getResult()
        ;
    }
}
