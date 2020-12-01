<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\View;

use App\Domain\Common\State\AbstractState;

class AdvertisementListItemView
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
     * @var AbstractState
     */
    private AbstractState $state;

    /**
     * @var string
     */
    private string $categoryId;

    /**
     * @var string
     */
    private string $categoryName;

    /**
     * @var float
     */
    private float $price;

    /**
     * @var string
     */
    private string $currency;

    /**
     * @var string|null
     */
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
     * @return AbstractState
     */
    public function getState(): AbstractState
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return string|null
     */
    public function getFeaturedImage(): ?string
    {
        return $this->featuredImage;
    }
}
