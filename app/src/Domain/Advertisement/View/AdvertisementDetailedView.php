<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\View;

use DateTimeInterface;

class AdvertisementDetailedView
{
    private string $id;

    private string $title;

    private string $description;

    private float $price;

    private string $currency;

    private string $authorId;

    private string $authorFullName;

    private string $authorEmail;

    private DateTimeInterface $createdAt;

    /**
     * @var array|AttachmentView[]
     */
    private array $attachments;

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

    public function getId(): string
    {
        return $this->id;
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

    public function getAuthorId(): string
    {
        return $this->authorId;
    }

    public function getAuthorFullName(): string
    {
        return $this->authorFullName;
    }

    public function getAuthorEmail(): string
    {
        return $this->authorEmail;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return AttachmentView[]|array
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @param AttachmentView[]|array $attachments
     * @return AdvertisementDetailedView
     */
    public function setAttachments(array $attachments): self
    {
        $this->attachments = $attachments;

        return $this;
    }
}
