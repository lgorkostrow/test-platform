<?php

namespace App\Infrastructure\Advertisement\Repository\Doctrine;

use App\Domain\Advertisement\Entity\Advertisement;
use App\Domain\Advertisement\Query\GetPublishedAdvertisementsQuery;
use App\Domain\Advertisement\Query\GetUserAdvertisementsQuery;
use App\Domain\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Domain\Advertisement\State\Advertisement\PublishedState;
use App\Domain\Common\Repository\PaginatedQueryResult;
use App\Infrastructure\Common\Repository\AbstractDoctrineRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Advertisement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advertisement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advertisement[]    findAll()
 * @method Advertisement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertisementRepository extends AbstractDoctrineRepository implements AdvertisementRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advertisement::class);
    }

    public function findUserAdvertisements(GetUserAdvertisementsQuery $query): PaginatedQueryResult
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('a.id')
            ->addSelect('a.state')
            ->addSelect('a.description.title as title')
            ->addSelect('a.price.value as price')
            ->addSelect('a.price.currency as currency')
            ->addSelect('category.id as categoryId')
            ->addSelect('category.name as categoryName')
            ->from(Advertisement::class, 'a')
            ->leftJoin('a.category', 'category')
        ;

        $qb
            ->where('a.author = :authorId')
            ->setParameter('authorId', $query->getUserId())
        ;

        if ($query->getState()) {
            $qb
                ->andWhere('a.state = :state')
                ->andWhere('a.state = :state')
                ->setParameter('state', $query->getState())
            ;
        }

        if ($query->getCategoryId()) {
            $qb
                ->andWhere('a.category = :categoryId')
                ->setParameter('categoryId', $query->getCategoryId())
            ;
        }

        return $this->paginate($qb, $query);
    }

    public function findPublishedAdvertisements(GetPublishedAdvertisementsQuery $query): PaginatedQueryResult
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('a.id')
            ->addSelect('a.state')
            ->addSelect('a.description.title as title')
            ->addSelect('a.price.value as price')
            ->addSelect('a.price.currency as currency')
            ->addSelect('category.id as categoryId')
            ->addSelect('category.name as categoryName')
            ->from(Advertisement::class, 'a')
            ->innerJoin('a.category', 'category', Join::WITH, 'category.id = :categoryId')
            ->where('a.state = :publishedState')
            ->setParameters([
                'categoryId' => $query->getCategoryId(),
                'publishedState' => PublishedState::NAME,
            ])
        ;

        if (null !== $title = $query->getTitle()) {
            $qb
                ->andWhere($qb->expr()->like('a.description.title', ':title'))
                ->setParameter('title', "%$title%")
            ;
        }

        return $this->paginate($qb, $query);
    }
}
