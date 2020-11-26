<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Entity;

use App\Domain\File\Entity\File;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class AdvertisementAttachment
{
    /**
     * @var Advertisement
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Advertisement::class, inversedBy="attachments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private Advertisement $record;

    /**
     * @var File
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=File::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private File $file;

    public function __construct(Advertisement $record, File $file)
    {
        $this->record = $record;
        $this->file = $file;
    }

    /**
     * @return Advertisement
     */
    public function getRecord(): Advertisement
    {
        return $this->record;
    }

    /**
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }
}
