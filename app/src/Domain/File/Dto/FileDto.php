<?php

declare(strict_types=1);

namespace App\Domain\File\Dto;

class FileDto
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string
     */
    protected string $storageType;

    /**
     * @var string
     */
    protected string $originalName;

    /**
     * @var string
     */
    protected string $originalExtension;

    /**
     * @var string
     */
    protected string $path;

    /**
     * @var string
     */
    protected string $mimeType;

    /**
     * @var int
     */
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
    public function getStorageType(): string
    {
        return $this->storageType;
    }

    /**
     * @return string
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * @return string
     */
    public function getOriginalExtension(): string
    {
        return $this->originalExtension;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }
}
