<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Currency\Strategy;

use App\Infrastructure\Currency\Strategy\ExchangeRatesProvider\PBExchangeRatesProvider;
use App\Tests\AbstractKernelTestCase;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class PBExchangeRatesProviderTest extends AbstractKernelTestCase
{
    /**
     * @test
     *
     * @dataProvider successResponseDataProvider
     *
     * @param array $data
     */
    public function shouldUpdateExchangeRates(array $data): void
    {
        $client = new MockHttpClient(new MockResponse(json_encode($data)));

        $provider = new PBExchangeRatesProvider('http://test.com', $client);

        $exchangeRates = $provider->provide();

        self::assertIsArray($exchangeRates);
        self::assertNotEmpty($exchangeRates);

        foreach ($exchangeRates as $key => $exchangeRate) {
            self::assertEquals($data[$key]['ccy'], $exchangeRate->getCcy());
            self::assertEquals($data[$key]['buy'], $exchangeRate->getBuy());
            self::assertEquals($data[$key]['sale'], $exchangeRate->getSale());
        }
    }

    /**
     * @test
     *
     * @dataProvider statusCodeProvider
     *
     * @param int $code
     * @param string $exception
     */
    public function shouldThrowException(int $code, string $exception): void
    {
        $this->expectException($exception);

        $client = new MockHttpClient(new MockResponse('', ['http_code' => $code]));

        $provider = new PBExchangeRatesProvider('http://test.com', $client);

        $provider->provide();
    }

    public function successResponseDataProvider(): array
    {
        return [
            [
                'data' => [
                    [
                        'ccy' => 'EUR',
                        'base_ccy' => 'UAH',
                        'buy' => 29.0,
                        'sale' => 30.1,
                    ],
                    [
                        'ccy' => 'USD',
                        'base_ccy' => 'UAH',
                        'buy' => 25.5,
                        'sale' => 26.1,
                    ],
                ],
            ],
            [
                'data' => [
                    [
                        'ccy' => 'EUR',
                        'base_ccy' => 'UAH',
                        'buy' => 10,
                        'sale' => 15,
                    ],
                    [
                        'ccy' => 'USD',
                        'base_ccy' => 'UAH',
                        'buy' => 14,
                        'sale' => 15,
                    ],
                ],
            ],
        ];
    }

    public function statusCodeProvider(): array
    {
        return [
            [
                'code' => 400,
                'exception' => ClientException::class,
            ],
            [
                'code' => 405,
                'exception' => ClientException::class,
            ],
            [
                'code' => 500,
                'exception' => ServerException::class,
            ],
            [
                'code' => 504,
                'exception' => ServerException::class,
            ],
        ];
    }
}
