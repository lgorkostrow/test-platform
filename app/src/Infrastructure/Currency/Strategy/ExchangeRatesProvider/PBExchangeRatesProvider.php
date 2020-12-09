<?php

declare(strict_types=1);

namespace App\Infrastructure\Currency\Strategy\ExchangeRatesProvider;

use App\Domain\Currency\Enum\ExchangeRatesProviderEnum;
use App\Domain\Currency\Provider\ExchangeRatesProviderInterface;
use App\Domain\Currency\Repository\CurrencyRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PBExchangeRatesProvider implements ExchangeRatesProviderInterface
{
    private string $apiUrl;

    private CurrencyRepositoryInterface $repository;

    private HttpClientInterface $client;

    public function __construct(string $apiUrl, CurrencyRepositoryInterface $repository, HttpClientInterface $client)
    {
        $this->apiUrl = $apiUrl;
        $this->repository = $repository;
        $this->client = $client;
    }

    public function supports(string $provider): bool
    {
        return ExchangeRatesProviderEnum::PB_PROVIDER === $provider;
    }

    public function update(): void
    {
        $response = $this->client->request(Request::METHOD_GET, $this->apiUrl);
        $data = $response->toArray();

        $ccyList = array_map(static fn(array $item) => $item['ccy'], $data);
        $data = array_combine($ccyList, $data);

        foreach ($currencies = $this->repository->findByCcy($ccyList) as $currency) {
            if (!isset($data[$currency->getCcy()])) {
                continue;
            }

            $currency->updateExchangeRate(
                (float)$data[$currency->getCcy()]['buy'],
                (float)$data[$currency->getCcy()]['sale']
            );
        }

        $this->repository->saveAll($currencies);
    }
}
