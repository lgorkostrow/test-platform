<?php

namespace App\Domain\Currency\Provider;

use App\Domain\Currency\ValueObject\CurrencyExchangeRate;

interface ExchangeRatesProviderInterface
{
    public function supports(string $provider): bool;

    /**
     * @return CurrencyExchangeRate[]
     */
    public function provide(): array;
}
