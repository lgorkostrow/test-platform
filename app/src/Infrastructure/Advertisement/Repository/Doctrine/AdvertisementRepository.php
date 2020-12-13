<?php

namespace App\Infrastructure\Advertisement\Repository\Doctrine;

use App\Domain\Advertisement\Entity\Advertisement;
use App\Domain\Advertisement\Entity\AdvertisementAttachment;
use App\Domain\Advertisement\Query\GetPublishedAdvertisementsQuery;
use App\Domain\Advertisement\Query\GetReadyForReviewAdvertisementsQuery;
use App\Domain\Advertisement\Query\GetUserAdvertisementsQuery;
use App\Domain\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Domain\Advertisement\State\Advertisement\OnReviewState;
use App\Domain\Advertisement\State\Advertisement\PublishedState;
use App\Domain\Advertisement\View\AdvertisementDetailedView;
use App\Domain\Advertisement\View\AttachmentView;
use App\Domain\Common\Repository\PaginatedQueryResult;
use App\Domain\Currency\Entity\Currency;
use App\Infrastructure\Common\Repository\AbstractDoctrineRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\Advertisement\View\AdvertisementListItemView;

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
        $qb = $this->buildListQueryBuilder()
            ->leftJoin('a.category', 'category')
            ->where('a.author = :authorId')
            ->setParameter('authorId', $query->getUserId())
        ;

        if ($query->getState()) {
            $qb
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
        $qb = $this->buildListQueryBuilder()
            ->innerJoin('a.category', 'category', Join::WITH, 'category.id = :categoryId')
            ->innerJoin(
                Currency::class,
                'currency',
                Join::WITH,
                'currency.ccy = a.price.currency'
            )
            ->where('a.state = :publishedState')
            ->setParameters([
                'categoryId' => $query->getCategoryId(),
                'publishedState' => PublishedState::NAME,
            ])
        ;

        if (null !== $title = $query->getTitle()) {
            $qb
                ->andWhere($qb->expr()->like('a.description.title', ':title'))
                ->setParameter('title', "$title%")
            ;
        }

        if (isset($query->getPrice()['min'])) {
            $qb
                ->andWhere('(a.price.value * currency.sale) >= :minPrice')
                ->setParameter('minPrice', $query->getPrice()['min'])
            ;
        }

        if (isset($query->getPrice()['max'])) {
            $qb
                ->andWhere('(a.price.value * currency.sale) <= :maxPrice')
                ->setParameter('maxPrice', $query->getPrice()['max'])
            ;
        }

        return $this->paginate($qb, $query);
    }

    public function findReadyForReviewAdvertisements(GetReadyForReviewAdvertisementsQuery $query): PaginatedQueryResult
    {
        $qb = $this->buildListQueryBuilder()
            ->leftJoin('a.category', 'category')
            ->where('a.state = :state')
            ->setParameter('state', OnReviewState::NAME)
        ;

        if ($query->getCategoryId()) {
            $qb
                ->andWhere('a.category = :categoryId')
                ->setParameter('categoryId', $query->getCategoryId())
            ;
        }

        return $this->paginate($qb, $query);
    }

    public function findDetailedView(string $id): ?AdvertisementDetailedView
    {
        $advertisement = $this->_em->createQueryBuilder()
            ->select(
                sprintf(
                    'NEW %s(a.id, a.description.title, a.description.description, a.price.value, a.price.currency, author.id, CONCAT(author.personalData.firstName, \'\', author.personalData.lastName), author.personalData.email, a.createdAt)',
                    AdvertisementDetailedView::class,
                )
            )
            ->from(Advertisement::class, 'a')
            ->leftJoin('a.author', 'author')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if (!$advertisement) {
            return null;
        }

        $attachments = $this->_em->createQueryBuilder()
            ->select(
                sprintf(
                    'NEW %s(a.id, a.featured, file.storage, file.path)',
                    AttachmentView::class,
                )
            )
            ->from(AdvertisementAttachment::class, 'a')
            ->leftJoin('a.file', 'file')
            ->where('a.advertisement = :advertisementId')
            ->setParameter('advertisementId', $id)
            ->getQuery()
            ->getResult()
        ;

        return $advertisement->setAttachments($attachments);
    }

    private function buildListQueryBuilder(): QueryBuilder
    {
        return $this->_em->createQueryBuilder()
            ->select(
                sprintf(
                    'NEW %s(a.id, a.description.title, a.state, category.id, category.name, a.price.value, a.price.currency, file.id, file.storage, file.path)',
                    AdvertisementListItemView::class,
                )
            )
            ->from(Advertisement::class, 'a')
            ->leftJoin('a.attachments', 'attachment', Join::WITH, 'attachment.featured = true')
            ->leftJoin('attachment.file', 'file')
        ;
    }
}
