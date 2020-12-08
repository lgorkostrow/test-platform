<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Query;

use App\Domain\Advertisement\Dto\AdvertisementDetailedViewDto;
use App\Domain\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Domain\Common\Message\QueryHandlerInterface;

class GetAdvertisementQueryHandler implements QueryHandlerInterface
{
    private AdvertisementRepositoryInterface $advertisementRepository;

    public function __construct(AdvertisementRepositoryInterface $advertisementRepository)
    {
        $this->advertisementRepository = $advertisementRepository;
    }

    public function __invoke(GetAdvertisementQuery $query): ?AdvertisementDetailedViewDto
    {
        if (null === $view = $this->advertisementRepository->findDetailedView($query->getId())) {
            return null;
        }

        return AdvertisementDetailedViewDto::createFromAdvertisementDetailedView($view);
    }
}
