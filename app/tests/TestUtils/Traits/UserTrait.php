<?php

declare(strict_types=1);

namespace App\Tests\TestUtils\Traits;

use App\Domain\User\Entity\User;
use App\Domain\User\Enum\RoleEnum;
use App\Domain\User\ValueObject\PersonalData;
use Doctrine\ORM\EntityManagerInterface;

trait UserTrait
{
    protected ?EntityManagerInterface $entityManager;

    private function findRandomUser(bool $verified = true): User
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
            ->andWhere('u.emailConfirmed = :verified')
            ->setParameters([
                'adminRole' => '%' . RoleEnum::ROLE_ADMIN . '%',
                'managerRole' => '%' . RoleEnum::ROLE_MANAGER . '%',
                'verified' => $verified,
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    private function findUserExcept(User $user): User
    {
        $qb = $this->entityManager->createQueryBuilder();

        return $qb
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.id <> :id')
            ->andWhere('u.emailConfirmed = true')
            ->setParameter('id', $user->getId())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    private function findAdmin(): User
    {
        $qb = $this->entityManager->createQueryBuilder();

        return $qb
            ->select('u')
            ->from(User::class, 'u')
            ->where($qb->expr()->like('u.roles', ':role'))
            ->setParameter('role', '%' . RoleEnum::ROLE_ADMIN . '%')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    private function findManager(): User
    {
        $qb = $this->entityManager->createQueryBuilder();

        return $qb
            ->select('u')
            ->from(User::class, 'u')
            ->where($qb->expr()->notLike('u.roles', ':adminRole'))
            ->andWhere($qb->expr()->like('u.roles', ':managerRole'))
            ->setParameters([
                'adminRole' => '%' . RoleEnum::ROLE_ADMIN . '%',
                'managerRole' => '%' . RoleEnum::ROLE_MANAGER . '%',
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    private function getUserPersonalData(User $user): PersonalData
    {
        $reflectionClass = new \ReflectionClass(User::class);
        $property = $reflectionClass->getProperty('personalData');
        $property->setAccessible(true);

        return $property->getValue($user);
    }
}
