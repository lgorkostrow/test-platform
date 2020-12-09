<?php

namespace App\Domain\Currency\Repository;

use App\Domain\Common\Repository\BaseEntityRepositoryInterface;
use App\Domain\Currency\Entity\Currency;

interface CurrencyRepositoryInterface extends BaseEntityRepositoryInterface
{
    /**
     * @param string[] $ccy
     * @return Currency[]
     */
    public function findByCcy(array $ccy): array;
}
