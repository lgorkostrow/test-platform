<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use App\Domain\Common\Enum\CurrencyEnum;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * @ORM\Embeddable
 */
class Price
{
    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private float $value;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10)
     */
    private string $currency;

    public function __construct(float $value, string $currency)
    {
        if (!in_array($currency, CurrencyEnum::VALID_CHOICES, true)) {
            throw new LogicException('UNDEFINED_CURRENCY');
        }

        $this->value = $value;
        $this->currency = $currency;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
