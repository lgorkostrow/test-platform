<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Dto;

use App\Domain\Advertisement\View\AttachmentView;

class AdvertisementAttachmentSimpleDto
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var bool
     */
    private bool $featured;

    /**
     * @var string
     */
    private string $path;

    public function __construct(string $id, bool $featured, string $path)
    {
        $this->id = $id;
        $this->featured = $featured;
        $this->path = $path;
    }

    public static function createFromView(AttachmentView $view): self
    {
        return new self($view->getId(), $view->isFeatured(), $view->getPath());
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isFeatured(): bool
    {
        return $this->featured;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
