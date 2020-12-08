<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Dto;

use App\Domain\Advertisement\View\CategoryListItemView;

class CategoryListItemDto
{
    private string $id;

    private string $name;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function createFromView(CategoryListItemView $view): self
    {
        return new self($view->getId(), $view->getName());
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
