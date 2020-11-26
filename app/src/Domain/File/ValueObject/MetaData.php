<?php

declare(strict_types=1);

namespace App\Domain\File\ValueObject;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class MetaData
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * in KB
     *
     * @var float
     *
     * @ORM\Column(type="decimal", scale=1, options={"default" : 0})
     */
    private float $size;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private string $mimeType;

    public function __construct(string $name, float $size, string $mimeType)
    {
        $this->name = $name;
        $this->size = $size;
        $this->mimeType = $mimeType;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getSize(): float
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }
}
