<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Dto;

class PriceDto
{
    /**
     * @var float
     */
    private float $price;

    /**
     * @var string
     */
    private string $currency;

    public function __construct(float $price, string $currency)
    {
        $this->price = $price;
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
