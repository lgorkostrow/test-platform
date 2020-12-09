<?php

declare(strict_types=1);

namespace App\Domain\Currency\UseCase;

use App\Domain\Common\Message\AsyncMessageInterface;

class UpdateExchangeRatesCommand implements AsyncMessageInterface
{
    private string $provider;

    public function __construct(string $provider)
    {
        $this->provider = $provider;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }
}
