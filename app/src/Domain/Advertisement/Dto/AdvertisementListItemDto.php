<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Dto;

use App\Domain\Common\State\AbstractState;

class AdvertisementListItemDto
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var string
     */
    private string $state;

    /**
     * @var CategoryListItemDto
     */
    private CategoryListItemDto $category;

    /**
     * @var PriceDto
     */
    private PriceDto $price;

    public function __construct(string $id, string $title, AbstractState $state, CategoryListItemDto $category, PriceDto $price)
    {
        $this->id = $id;
        $this->title = $title;
        $this->state = $state::NAME;
        $this->category = $category;
        $this->price = $price;
    }

    public static function createFromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['title'],
            $data['state'],
            new CategoryListItemDto($data['categoryId'] ?? '', $data['categoryName'] ?? ''),
            new PriceDto((float)$data['price'] ?? 0, $data['currency'] ?? '')
        );
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return CategoryListItemDto
     */
    public function getCategory(): CategoryListItemDto
    {
        return $this->category;
    }

    /**
     * @return PriceDto
     */
    public function getPrice(): PriceDto
    {
        return $this->price;
    }
}
