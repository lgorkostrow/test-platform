<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Query;

use App\Domain\Common\Message\AbstractListQuery;

class GetReadyForReviewAdvertisementsQuery extends AbstractListQuery
{
    private ?string $categoryId;

    public function __construct(int $limit, int $offset, ?string $categoryId)
    {
        parent::__construct($limit, $offset);

        $this->categoryId = $categoryId;
    }

    public function getCategoryId(): ?string
    {
        return $this->categoryId;
    }
}