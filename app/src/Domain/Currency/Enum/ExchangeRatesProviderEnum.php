<?php

declare(strict_types=1);

namespace App\Domain\Currency\Enum;

class ExchangeRatesProviderEnum
{
    public const PB_PROVIDER = 'pb';

    public const VALID_CHOICES = [
        self::PB_PROVIDER,
    ];
}
