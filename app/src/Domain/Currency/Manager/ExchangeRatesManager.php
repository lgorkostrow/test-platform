<?php

declare(strict_types=1);

namespace App\Domain\Currency\Manager;

use App\Domain\Currency\Provider\ExchangeRatesProviderInterface;
use App\Domain\Currency\Repository\CurrencyRepositoryInterface;
use App\Domain\Currency\ValueObject\CurrencyExchangeRate;
use InvalidArgumentException;

class ExchangeRatesManager
{
    /**
     * @var iterable<ExchangeRatesProviderInterface>
     */
    private iterable $strategies;

    private CurrencyRepositoryInterface $repository;

    public function __construct(iterable $strategies, CurrencyRepositoryInterface $repository)
    {
        $this->strategies = $strategies;
        $this->repository = $repository;
    }

    public function update(string $provider): void
    {
        $exchangeRates = $this->getProvider($provider)->provide();
        $ccyList = array_map(static fn(CurrencyExchangeRate $exchangeRate) => $exchangeRate->getCcy(), $exchangeRates);
        $data = array_combine($ccyList, $exchangeRates);

        foreach ($currencies = $this->repository->findByCcy($ccyList) as $currency) {
            if (!isset($data[$currency->getCcy()])) {
                continue;
            }

            $currency->updateExchangeRate(
                $data[$currency->getCcy()]->getBuy(),
                $data[$currency->getCcy()]->getSale(),
            );
        }

        $this->repository->saveAll($currencies);
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
