<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Query;

use App\Domain\Common\Message\AbstractListQuery;

class GetUserAdvertisementsQuery extends AbstractListQuery
{
    private string $userId;

    private ?string $state;

    private ?string $categoryId;

    public function __construct(int $limit, int $offset, string $userId, ?string $state, ?string $categoryId)
    {
        parent::__construct($limit, $offset);

        $this->userId = $userId;
        $this->state = $state;
        $this->categoryId = $categoryId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function getCategoryId(): ?string
    {
        return $this->categoryId;
    }
}
