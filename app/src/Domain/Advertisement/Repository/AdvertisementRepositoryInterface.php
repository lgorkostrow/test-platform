<?php

namespace App\Domain\Advertisement\Repository;

use App\Domain\Advertisement\Entity\Advertisement;
use App\Domain\Common\Repository\BaseEntityRepositoryInterface;

interface AdvertisementRepositoryInterface extends BaseEntityRepositoryInterface
{
    /**
     * @param $id
     * @return Advertisement|null
     */
    public function find($id);
}
