<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Query;

use App\Domain\Advertisement\Dto\CategoryListItemDto;
use App\Domain\Advertisement\Repository\CategoryRepositoryInterface;
use App\Domain\Advertisement\View\CategoryListItemView;
use App\Domain\Common\Message\QueryHandlerInterface;

class GetCategoriesQueryHandler implements QueryHandlerInterface
{
    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetCategoriesQuery $query)
    {
        $paginatedData = $this->repository->findAllCategories($query);
        $paginatedData->setData(
            array_map(function (CategoryListItemView $view) {
                return CategoryListItemDto::createFromView($view);
            }, $paginatedData->getData())
        );

        return $paginatedData;
    }
}
