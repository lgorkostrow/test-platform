<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Dto;

use App\Domain\Advertisement\View\AdvertisementDetailedView;
use App\Domain\Advertisement\View\AttachmentView;
use DateTimeInterface;

class AdvertisementDetailedViewDto
{
    private string $id;

    private string $title;

    private string $description;

    private PriceDto $price;

    private AuthorDto $author;

    private DateTimeInterface $createdAt;

    /**
     * @var AdvertisementAttachmentSimpleDto[]
     */
    private array $attachments;

    public function __construct(
        string $id,
        string $title,
        string $description,
        PriceDto $price,
        AuthorDto $author,
        DateTimeInterface $createdAt,
        array $attachments
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->author = $author;
        $this->createdAt = $createdAt;
        $this->attachments = $attachments;
    }

    public static function createFromAdvertisementDetailedView(AdvertisementDetailedView $view): self
    {
        return new self(
            $view->getId(),
            $view->getTitle(),
            $view->getDescription(),
            new PriceDto($view->getPrice(), $view->getCurrency()),
            new AuthorDto($view->getAuthorId(), $view->getAuthorFullName(), $view->getAuthorEmail()),
            $view->getCreatedAt(),
            array_map(static fn(AttachmentView $view) => AdvertisementAttachmentSimpleDto::createFromView($view), $view->getAttachments()),
        );
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

    public function getPrice(): PriceDto
    {
        return $this->price;
    }

    public function getAuthor(): AuthorDto
    {
        return $this->author;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return AdvertisementAttachmentSimpleDto[]
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }
}
