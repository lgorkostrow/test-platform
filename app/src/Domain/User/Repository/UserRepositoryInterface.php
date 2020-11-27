<?php

namespace App\Domain\User\Repository;

use App\Domain\Common\Repository\BaseEntityRepositoryInterface;
use App\Domain\User\Entity\User;

interface UserRepositoryInterface extends BaseEntityRepositoryInterface
{
    /**
     * @param $id
     * @return User|null
     */
    public function find($id);

    /**
     * @param string $id
     * @return User
     */
    public function findOrFail(string $id): object;

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;
}
