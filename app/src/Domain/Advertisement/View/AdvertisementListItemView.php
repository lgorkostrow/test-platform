<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\View;

use App\Domain\Common\State\AbstractState;

class AdvertisementListItemView
{
    private string $id;

    private string $title;

    private AbstractState $state;

    private string $categoryId;

    private string $categoryName;

    private float $price;

    private string $currency;

    private ?string $featuredImage;

    public function __construct(
        string $id,
        string $title,
        AbstractState $state,
        string $categoryId,
        string $categoryName,
        float $price,
        string $currency,
        ?string $featuredImage
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->state = $state;
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
        $this->price = $price;
        $this->currency = $currency;
        $this->featuredImage = $featuredImage;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getState(): AbstractState
    {
        return $this->state;
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getFeaturedImage(): ?string
    {
        return $this->featuredImage;
    }
}
