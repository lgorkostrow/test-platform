<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Dto;

use App\Domain\Advertisement\View\AdvertisementListItemView;
use App\Domain\Common\State\AbstractState;

class AdvertisementListItemDto
{
    private string $id;

    private string $title;

    private string $state;

    private CategoryListItemDto $category;

    private PriceDto $price;

    private ?AdvertisementAttachmentSimpleDto $featuredImage;

    public function __construct(
        string $id,
        string $title,
        AbstractState $state,
        CategoryListItemDto $category,
        PriceDto $price,
        ?AdvertisementAttachmentSimpleDto $featuredImage
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->state = $state::NAME;
        $this->category = $category;
        $this->price = $price;
        $this->featuredImage = $featuredImage;
    }

    public static function createFromAdvertisementListItemView(AdvertisementListItemView $view): self
    {
        $featuredImage = $view->getFileId()
            ? new AdvertisementAttachmentSimpleDto($view->getFileId(), true, $view->getFileStorage(), $view->getFeaturedImage())
            : null;

        return new self(
            $view->getId(),
            $view->getTitle(),
            $view->getState(),
            new CategoryListItemDto($view->getCategoryId(), $view->getCategoryName()),
            new PriceDto($view->getPrice(), $view->getCurrency()),
            $featuredImage,
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

    public function getState(): string
    {
        return $this->state;
    }

    public function getCategory(): CategoryListItemDto
    {
        return $this->category;
    }

    public function getPrice(): PriceDto
    {
        return $this->price;
    }

    public function getFeaturedImage(): ?AdvertisementAttachmentSimpleDto
    {
        return $this->featuredImage;
    }
}
