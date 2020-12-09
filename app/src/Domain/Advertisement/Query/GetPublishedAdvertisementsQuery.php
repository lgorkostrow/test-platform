<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Query;

use App\Domain\Common\Message\AbstractListQuery;

class GetPublishedAdvertisementsQuery extends AbstractListQuery
{
    private string $categoryId;

    private ?string $title;

    private ?float $price;

    public function __construct(int $limit, int $offset, string $categoryId, ?string $title, ?float $price = null)
    {
        parent::__construct($limit, $offset);

        $this->categoryId = $categoryId;
        $this->title = $title;
        $this->price = $price;
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }
}
