<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Dto;

class AdvertisementDto
{
    private string $title;

    private string $description;

    private float $price;

    private string $currency;

    private string $categoryId;

    private string $userId;

    /**
     * @var array|AdvertisementAttachmentDto[]
     */
    private array $attachments;

    public function __construct(
        string $title,
        string $description,
        float $price,
        string $currency,
        string $categoryId,
        string $userId,
        array $attachments
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->currency = $currency;
        $this->categoryId = $categoryId;
        $this->userId = $userId;
        $this->attachments = $attachments;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return AdvertisementAttachmentDto[]|array
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }
}
