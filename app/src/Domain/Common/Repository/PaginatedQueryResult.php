<?php

declare(strict_types=1);

namespace App\Domain\Common\Repository;

class PaginatedQueryResult
{
    /**
     * @var array
     */
    private array $data;

    /**
     * @var int|null
     */
    private ?int $limit;

    /**
     * @var int|null
     */
    private ?int $offset;

    /**
     * @var int|null
     */
    private ?int $count;

    public function __construct(array $data, ?int $limit, ?int $offset, ?int $count)
    {
        $this->data = $data;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->count = $count;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return PaginatedQueryResult
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @return int|null
     */
    public function getCount(): ?int
    {
        return $this->count;
    }
}
