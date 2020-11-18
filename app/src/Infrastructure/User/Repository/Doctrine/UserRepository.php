<?php

namespace App\Infrastructure\User\Repository\Doctrine;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Common\Repository\AbstractDoctrineRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends AbstractDoctrineRepository implements UserRepositoryInterface, UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string $username
     * @return User|null
     */
    public function loadUserByUsername($username): ?User
    {
        return $this->findOneBy([
            'personalData.email' => $username,
            'emailConfirmed' => true,
        ]);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['personalData.email' => $email]);
    }
}
