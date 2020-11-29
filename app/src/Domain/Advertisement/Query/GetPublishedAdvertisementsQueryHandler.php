<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Query;

use App\Domain\Advertisement\Dto\AdvertisementListItemDto;
use App\Domain\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Domain\Common\Message\QueryHandlerInterface;
use App\Domain\Common\Repository\PaginatedQueryResult;

class GetPublishedAdvertisementsQueryHandler implements QueryHandlerInterface
{
    /**
     * @var AdvertisementRepositoryInterface
     */
    private AdvertisementRepositoryInterface $advertisementRepository;

    public function __construct(AdvertisementRepositoryInterface $advertisementRepository)
    {
        $this->advertisementRepository = $advertisementRepository;
    }

    public function __invoke(GetPublishedAdvertisementsQuery $query): PaginatedQueryResult
    {
        $paginatedData = $this->advertisementRepository->findPublishedAdvertisements($query);
        $paginatedData->setData(
            array_map(fn(array $item) => AdvertisementListItemDto::createFromArray($item), $paginatedData->getData())
        );

        return $paginatedData;
    }
}
