<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Dto;

class AdvertisementDto
{
    /**
     * @var string
     */
    private string $title;

    /**
     * @var string
     */
    private string $description;

    /**
     * @var float
     */
    private float $price;

    /**
     * @var string
     */
    private string $currency;

    /**
     * @var string
     */
    private string $categoryId;

    /**
     * @var string
     */
    private string $userId;

    public function __construct(
        string $title,
        string $description,
        float $price,
        string $currency,
        string $categoryId,
        string $userId
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->currency = $currency;
        $this->categoryId = $categoryId;
        $this->userId = $userId;
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
    public function getDescription(): string
    {
        return $this->description;
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
     * @return string
     */
    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }
}
