<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Dto;

use App\Domain\Advertisement\View\AdvertisementDetailedView;
use App\Domain\Advertisement\View\AttachmentView;
use DateTimeInterface;

class AdvertisementDetailedViewDto
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
     * @var PriceDto
     */
    private PriceDto $price;

    /**
     * @var AuthorDto
     */
    private AuthorDto $author;

    /**
     * @var DateTimeInterface
     */
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
            array_map(fn(AttachmentView $view) => AdvertisementAttachmentSimpleDto::createFromView($view), $view->getAttachments()),
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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return PriceDto
     */
    public function getPrice(): PriceDto
    {
        return $this->price;
    }

    /**
     * @return AuthorDto
     */
    public function getAuthor(): AuthorDto
    {
        return $this->author;
    }

    /**
     * @return DateTimeInterface
     */
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
