<?php

namespace App\Domain\Currency\Provider;

interface ExchangeRatesProviderInterface
{
    public function supports(string $provider): bool;

    public function update(): void;
}
