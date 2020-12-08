<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Dto;

class PriceDto
{
    private float $price;

    private string $currency;

    public function __construct(float $price, string $currency)
    {
        $this->price = $price;
        $this->currency = $currency;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
