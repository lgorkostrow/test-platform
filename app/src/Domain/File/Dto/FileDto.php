<?php

declare(strict_types=1);

namespace App\Domain\File\Dto;

class FileDto
{
    protected string $id;

    protected string $storageType;

    protected string $originalName;

    protected string $originalExtension;

    protected string $path;

    protected string $mimeType;

    protected int $size;

    public function __construct(
        string $id,
        string $storageType,
        string $originalName,
        string $originalExtension,
        string $path,
        string $mimeType,
        int $size
    ) {
        $this->id = $id;
        $this->storageType = $storageType;
        $this->originalName = $originalName;
        $this->originalExtension = $originalExtension;
        $this->path = $path;
        $this->mimeType = $mimeType;
        $this->size = $size;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStorageType(): string
    {
        return $this->storageType;
    }

    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    public function getOriginalExtension(): string
    {
        return $this->originalExtension;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
