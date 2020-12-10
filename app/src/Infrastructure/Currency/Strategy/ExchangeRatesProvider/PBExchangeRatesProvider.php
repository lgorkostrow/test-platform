<?php

declare(strict_types=1);

namespace App\Infrastructure\Currency\Strategy\ExchangeRatesProvider;

use App\Domain\Currency\Enum\ExchangeRatesProviderEnum;
use App\Domain\Currency\Provider\ExchangeRatesProviderInterface;
use App\Domain\Currency\ValueObject\CurrencyExchangeRate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PBExchangeRatesProvider implements ExchangeRatesProviderInterface
{
    private string $apiUrl;

    private HttpClientInterface $client;

    public function __construct(string $apiUrl, HttpClientInterface $client)
    {
        $this->apiUrl = $apiUrl;
        $this->client = $client;
    }

    public function supports(string $provider): bool
    {
        return ExchangeRatesProviderEnum::PB_PROVIDER === $provider;
    }

    /**
     * @return CurrencyExchangeRate[]
     */
    public function provide(): array
    {
        $response = $this->client->request(Request::METHOD_GET, $this->apiUrl);

        return array_map(
            static fn(array $item) => new CurrencyExchangeRate($item['ccy'], (float)$item['buy'], (float)$item['sale']),
            $response->toArray()
        );
    }
}
