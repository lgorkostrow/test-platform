<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\View;

class AttachmentView
{
    private string $id;

    private bool $featured;

    private string $storage;

    private string $path;

    public function __construct(string $id, bool $featured, string $storage, string $path)
    {
        $this->id = $id;
        $this->featured = $featured;
        $this->storage = $storage;
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

    public function getStorage(): string
    {
        return $this->storage;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
