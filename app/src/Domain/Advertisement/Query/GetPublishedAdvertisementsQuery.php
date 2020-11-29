<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Query;

use App\Domain\Common\Message\AbstractListQuery;

class GetPublishedAdvertisementsQuery extends AbstractListQuery
{
    /**
     * @var string
     */
    private string $categoryId;

    /**
     * @var string|null
     */
    private ?string $title;

    public function __construct(int $limit, int $offset, string $categoryId, ?string $title)
    {
        parent::__construct($limit, $offset);

        $this->categoryId = $categoryId;
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }
}
