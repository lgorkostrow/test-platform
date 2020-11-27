<?php

namespace App\Domain\Common\Repository;

use App\Domain\Common\Exception\EntityNotFoundException;

interface BaseEntityRepositoryInterface
{
    /**
     * @param string $id
     * @return object
     *
     * @throws EntityNotFoundException
     */
    public function findOrFail(string $id): object;

    /**
     * @param object $entity
     * @return object
     */
    public function save(object $entity): object;

    /**
     * @param object[] $entities
     * @return object[]
     */
    public function saveAll(array $entities): array;

    /**
     * @param object $entity
     */
    public function remove(object $entity): void;
}
