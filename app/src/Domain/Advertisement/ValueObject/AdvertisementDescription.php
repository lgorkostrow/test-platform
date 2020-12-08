<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\ValueObject;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class AdvertisementDescription
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private string $description;

    public function __construct(string $title, string $description)
    {
        $this->title = $title;
        $this->description = $description;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
