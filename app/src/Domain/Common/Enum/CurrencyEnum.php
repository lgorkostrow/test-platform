<?php

declare(strict_types=1);

namespace App\Domain\Common\Enum;

class CurrencyEnum
{
    const UAH = 'UAH';
    const USD = 'USD';
    const EUR = 'EUR';

    const VALID_CHOICES = [
        self::UAH,
        self::USD,
        self::EUR,
    ];
}
