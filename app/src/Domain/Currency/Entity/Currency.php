<?php

declare(strict_types=1);

namespace App\Domain\Currency\Entity;

use App\Domain\Common\Entity\Timestampable;
use App\Domain\Common\Entity\TimestampableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Currency\Repository\Doctrine\CurrencyRepository")
 */
class Currency implements TimestampableInterface
{
    use Timestampable;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=3)
     */
    private string $ccy;

    /**
     * @var float|null
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?float $buy;

    /**
     * @var float|null
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?float $sale;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 1})
     */
    private bool $enabled = true;

    /**
     * @var bool
     *
     * @ORM\Column(name="`default`", type="boolean", options={"default": 0})
     */
    private bool $default = false;

    public function __construct(string $ccy, float $buy = null, float $sale = null)
    {
        $this->ccy = $ccy;
        $this->buy = $buy;
        $this->sale = $sale;
    }

    public function getCcy(): string
    {
        return $this->ccy;
    }

    public function getBuy(): ?float
    {
        return $this->buy;
    }

    public function getSale(): ?float
    {
        return $this->sale;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function enable(): void
    {
        $this->enabled = true;
    }

    public function disable(): void
    {
        $this->enabled = false;
    }

    public function isDefault(): bool
    {
        return $this->default;
    }

    public function updateExchangeRate(float $buy, float $sale): void
    {
        $this->buy = $buy;
        $this->sale = $sale;
    }
}
