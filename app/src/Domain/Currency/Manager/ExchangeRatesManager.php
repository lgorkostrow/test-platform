<?php

declare(strict_types=1);

namespace App\Domain\Currency\Manager;

use App\Domain\Currency\Provider\ExchangeRatesProviderInterface;
use InvalidArgumentException;

class ExchangeRatesManager
{
    /**
     * @var iterable<ExchangeRatesProviderInterface>
     */
    private iterable $strategies;

    public function __construct(iterable $strategies)
    {
        $this->strategies = $strategies;
    }

    public function update(string $provider): void
    {
        $this->getProvider($provider)->update();
    }

    private function getProvider(string $provider): ExchangeRatesProviderInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($provider)) {
                return $strategy;
            }
        }

        throw new InvalidArgumentException('INVALID_PROVIDER');
    }
}
