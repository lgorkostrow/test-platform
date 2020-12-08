<?php

declare(strict_types=1);

namespace App\Domain\File\Entity;

use App\Domain\File\ValueObject\MetaData;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class File
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="guid", unique=true)
     */
    private string $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $path;

    /**
     * @var MetaData
     *
     * @ORM\Embedded(class=MetaData::class, columnPrefix=false)
     */
    private MetaData $metaData;

    public function __construct(string $id, string $path, MetaData $metaData)
    {
        $this->id = $id;
        $this->path = $path;
        $this->metaData = $metaData;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
