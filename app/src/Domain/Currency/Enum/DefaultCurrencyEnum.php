<?php

declare(strict_types=1);

namespace App\Domain\Currency\Enum;

class DefaultCurrencyEnum
{
    public const UAH = 'UAH';
    public const USD = 'USD';
    public const EUR = 'EUR';

    public const BASE_CURRENCY = self::UAH;

    public const LIST = [
        self::USD,
        self::EUR,
    ];
}
