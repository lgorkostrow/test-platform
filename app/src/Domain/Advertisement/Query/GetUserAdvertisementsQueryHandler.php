<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Query;

use App\Domain\Advertisement\Dto\AdvertisementListItemDto;
use App\Domain\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Domain\Advertisement\View\AdvertisementListItemView;
use App\Domain\Common\Message\QueryHandlerInterface;
use App\Domain\Common\Repository\PaginatedQueryResult;

class GetUserAdvertisementsQueryHandler implements QueryHandlerInterface
{
    private AdvertisementRepositoryInterface $advertisementRepository;

    public function __construct(AdvertisementRepositoryInterface $advertisementRepository)
    {
        $this->advertisementRepository = $advertisementRepository;
    }

    public function __invoke(GetUserAdvertisementsQuery $query): PaginatedQueryResult
    {
        $paginatedData = $this->advertisementRepository->findUserAdvertisements($query);
        $paginatedData->setData(
            array_map(static function (AdvertisementListItemView $view) {
                return AdvertisementListItemDto::createFromAdvertisementListItemView($view);
            }, $paginatedData->getData())
        );

        return $paginatedData;
    }
}
