<?php

namespace App\Infrastructure\Advertisement\Repository\Doctrine;

use App\Domain\Advertisement\Entity\Advertisement;
use App\Domain\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Infrastructure\Common\Repository\AbstractDoctrineRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Advertisement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advertisement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advertisement[]    findAll()
 * @method Advertisement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertisementRepository extends AbstractDoctrineRepository implements AdvertisementRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advertisement::class);
    }
}
