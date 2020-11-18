<?php

namespace App\Domain\Common\Repository;

interface BaseEntityRepositoryInterface
{
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
