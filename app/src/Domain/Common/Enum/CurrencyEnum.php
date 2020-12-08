<?php

declare(strict_types=1);

namespace App\Domain\Common\Enum;

class CurrencyEnum
{
    public const UAH = 'UAH';
    public const USD = 'USD';
    public const EUR = 'EUR';

    public const VALID_CHOICES = [
        self::UAH,
        self::USD,
        self::EUR,
    ];
}
