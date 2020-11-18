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
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;
}
