<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\View;

use DateTimeInterface;

class AdvertisementDetailedView
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
    private string $authorId;

    /**
     * @var string
     */
    private string $authorFullName;

    /**
     * @var string
     */
    private string $authorEmail;

    /**
     * @var DateTimeInterface
     */
    private DateTimeInterface $createdAt;

    public function __construct(
        string $id,
        string $title,
        string $description,
        float $price,
        string $currency,
        string $authorId,
        string $authorFullName,
        string $authorEmail,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->currency = $currency;
        $this->authorId = $authorId;
        $this->authorFullName = $authorFullName;
        $this->authorEmail = $authorEmail;
        $this->createdAt = $createdAt;
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
    public function getAuthorId(): string
    {
        return $this->authorId;
    }

    /**
     * @return string
     */
    public function getAuthorFullName(): string
    {
        return $this->authorFullName;
    }

    /**
     * @return string
     */
    public function getAuthorEmail(): string
    {
        return $this->authorEmail;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
