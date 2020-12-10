<?php

declare(strict_types=1);

namespace App\Domain\Currency\ValueObject;

class CurrencyExchangeRate
{
    private string $ccy;

    private float $buy;

    private float $sale;

    public function __construct(string $ccy, float $buy, float $sale)
    {
        $this->ccy = $ccy;
        $this->buy = $buy;
        $this->sale = $sale;
    }

    public function getCcy(): string
    {
        return $this->ccy;
    }

    public function getBuy(): float
    {
        return $this->buy;
    }

    public function getSale(): float
    {
        return $this->sale;
    }
}
