<?php

namespace App\Domain\Advertisement\Repository;

use App\Domain\Advertisement\Entity\Category;
use App\Domain\Advertisement\Query\GetCategoriesQuery;
use App\Domain\Common\Repository\BaseEntityRepositoryInterface;
use App\Domain\Common\Repository\PaginatedQueryResult;

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

    public function findAllCategories(GetCategoriesQuery $query): PaginatedQueryResult;
}
