<?php

declare(strict_types=1);

namespace App\Infrastructure\Common\Repository;

use App\Domain\Common\Exception\EntityNotFoundException;
use App\Domain\Common\Repository\BaseEntityRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractDoctrineRepository extends ServiceEntityRepository implements BaseEntityRepositoryInterface
{
    public function findOrFail(string $id): object
    {
        $entity = $this->find($id);
        if (!$entity) {
            throw new EntityNotFoundException();
        }

        return $entity;
    }

    /**
     * @param object $entity
     * @return object
     */
    public function save(object $entity): object
    {
        $this->_em->persist($entity);
        $this->_em->flush();

        return $entity;
    }

    /**
     * @param object[] $entities
     * @return object[]
     */
    public function saveAll(array $entities): array
    {
        foreach ($entities as $entity) {
            $this->_em->persist($entity);
        }

        $this->_em->flush();

        return $entities;
    }

    /**
     * @param object $entity
     */
    public function remove(object $entity): void
    {
        $this->_em->remove($entity);
        $this->_em->flush();
    }
}
