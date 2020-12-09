<?php

declare(strict_types=1);

namespace App\Infrastructure\Currency\Repository\Doctrine;

use App\Domain\Currency\Entity\Currency;
use App\Domain\Currency\Repository\CurrencyRepositoryInterface;
use App\Infrastructure\Common\Repository\AbstractDoctrineRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Currency|null find($id, $lockMode = null, $lockVersion = null)
 * @method Currency|null findOneBy(array $criteria, array $orderBy = null)
 * @method Currency[]    findAll()
 * @method Currency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRepository extends AbstractDoctrineRepository implements CurrencyRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    /**
     * @param string[] $ccy
     * @return Currency[]
     */
    public function findByCcy(array $ccy): array
    {
        return $this->findBy(['ccy' => $ccy]);
    }
}
