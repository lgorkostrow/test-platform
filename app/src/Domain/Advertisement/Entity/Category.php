<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Entity;

use App\Domain\Advertisement\Exception\CategoryIsNotParentException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Category
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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $name;

    /**
     * @var Category|null
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="subcategories")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private ?Category $parentCategory;

    /**
     * @var Collection<Category>
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parentCategory")
     */
    private Collection $subcategories;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_default", type="boolean")
     */
    private bool $default;

    public function __construct(string $id, string $name, ?Category $parentCategory = null)
    {
        if ($parentCategory && !$parentCategory->isParent()) {
            throw new CategoryIsNotParentException();
        }

        $this->id = $id;
        $this->name = $name;
        $this->parentCategory = $parentCategory;
        $this->default = false;

        $this->subcategories = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isParent(): bool
    {
        return empty($this->parentCategory);
    }
}
