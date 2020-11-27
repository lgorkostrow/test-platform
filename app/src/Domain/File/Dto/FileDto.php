<?php

declare(strict_types=1);

namespace App\Domain\File\Dto;

class FileDto
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $storageType;

    /**
     * @var string
     */
    private string $originalName;

    /**
     * @var string
     */
    private string $originalExtension;

    /**
     * @var string
     */
    private string $path;

    /**
     * @var string
     */
    private string $mimeType;

    /**
     * @var int
     */
    private int $size;

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
