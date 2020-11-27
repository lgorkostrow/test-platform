<?php

namespace App\Domain\Advertisement\Repository;

use App\Domain\Advertisement\Entity\Category;
use App\Domain\Common\Repository\BaseEntityRepositoryInterface;

interface CategoryRepositoryInterface extends BaseEntityRepositoryInterface
{
    /**
     * @param $id
     * @return Category|null
     */
    public function find($id);

    /**
     * @param string $id
     * @return Category
     */
    public function findOrFail(string $id): object;
}
