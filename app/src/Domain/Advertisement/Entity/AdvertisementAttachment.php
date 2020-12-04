<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Entity;

use App\Domain\File\Entity\File;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
class AdvertisementAttachment
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="guid", unique=true)
     */
    private string $id;

    /**
     * @var Advertisement
     *
     * @ORM\ManyToOne(targetEntity=Advertisement::class, inversedBy="attachments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private Advertisement $advertisement;

    /**
     * @var File
     *
     * @ORM\ManyToOne(targetEntity=File::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private File $file;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private bool $featured;

    private function __construct(Advertisement $advertisement, File $file, bool $featured = false)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->advertisement = $advertisement;
        $this->file = $file;
        $this->featured = $featured;
    }

    public static function create(Advertisement $attachment, File $file): self
    {
        return new self($attachment, $file);
    }

    public static function createFeatured(Advertisement $attachment, File $file): self
    {
        return new self($attachment, $file, true);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Advertisement
     */
    public function getAdvertisement(): Advertisement
    {
        return $this->advertisement;
    }

    /**
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }

    /**
     * @return bool
     */
    public function isFeatured(): bool
    {
        return $this->featured;
    }
}
