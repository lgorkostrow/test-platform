<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Query;

use App\Domain\Common\Message\AbstractListQuery;

class GetUserAdvertisementsQuery extends AbstractListQuery
{
    /**
     * @var string
     */
    private string $userId;

    /**
     * @var string|null
     */
    private ?string $state;

    /**
     * @var string|null
     */
    private ?string $categoryId;

    public function __construct(int $limit, int $offset, string $userId, ?string $state, ?string $categoryId)
    {
        parent::__construct($limit, $offset);

        $this->userId = $userId;
        $this->state = $state;
        $this->categoryId = $categoryId;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @return string|null
     */
    public function getCategoryId(): ?string
    {
        return $this->categoryId;
    }
}
