<?php

namespace App\Domain\Advertisement\Repository;

use App\Domain\Advertisement\Entity\Advertisement;
use App\Domain\Advertisement\Query\GetPublishedAdvertisementsQuery;
use App\Domain\Advertisement\Query\GetUserAdvertisementsQuery;
use App\Domain\Common\Repository\BaseEntityRepositoryInterface;
use App\Domain\Common\Repository\PaginatedQueryResult;

interface AdvertisementRepositoryInterface extends BaseEntityRepositoryInterface
{
    /**
     * @param $id
     * @return Advertisement|null
     */
    public function find($id);

    /**
     * @param string $id
     * @return Advertisement
     */
    public function findOrFail(string $id): object;

    /**
     * @param GetUserAdvertisementsQuery $query
     * @return PaginatedQueryResult
     */
    public function findUserAdvertisements(GetUserAdvertisementsQuery $query): PaginatedQueryResult;

    /**
     * @param GetPublishedAdvertisementsQuery $query
     * @return PaginatedQueryResult
     */
    public function findPublishedAdvertisements(GetPublishedAdvertisementsQuery $query): PaginatedQueryResult;
}
