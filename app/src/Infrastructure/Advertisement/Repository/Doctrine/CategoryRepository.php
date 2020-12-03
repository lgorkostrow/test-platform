<?php

namespace App\Infrastructure\Advertisement\Repository\Doctrine;

use App\Domain\Advertisement\Entity\Category;
use App\Domain\Advertisement\Query\GetCategoriesQuery;
use App\Domain\Advertisement\Repository\CategoryRepositoryInterface;
use App\Domain\Advertisement\View\CategoryListItemView;
use App\Domain\Common\Repository\PaginatedQueryResult;
use App\Infrastructure\Common\Repository\AbstractDoctrineRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends AbstractDoctrineRepository implements CategoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findAllCategories(GetCategoriesQuery $query): PaginatedQueryResult
    {
        $qb = $this->_em->createQueryBuilder()
            ->select(
                sprintf(
                    'NEW %s(c.id, c.name)',
                    CategoryListItemView::class,
                )
            )
            ->from(Category::class, 'c');


        return $this->paginate($qb, $query);
    }
}
