<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\View;

class AttachmentView
{
    private string $id;

    private bool $featured;

    private string $path;

    public function __construct(string $id, bool $featured, string $path)
    {
        $this->id = $id;
        $this->featured = $featured;
        $this->path = $path;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isFeatured(): bool
    {
        return $this->featured;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
