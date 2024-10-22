<?php

namespace App\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Enum\Currency;

#[ORM\Embeddable]
readonly class Money
{
    public function __construct(
        #[ORM\Column(type: 'float')]
        private float    $amount,

        #[ORM\Column(type: 'string', length: 3, enumType: Currency::class)]
        private Currency $currency,
    ) {
        if ($amount < 0) {
            throw new \InvalidArgumentException("Amount cannot be negative.");
        }
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function add(Money $money): Money
    {
        if ($this->currency !== $money->currency) {
            throw new \InvalidArgumentException("Currencies do not match.");
        }

        return new Money($this->amount + $money->amount, $this->currency);
    }

    public function subtract(Money $money): Money
    {
        if ($this->currency !== $money->currency) {
            throw new \InvalidArgumentException("Currencies do not match.");
        }

        return new Money($this->amount - $money->amount, $this->currency);
    }

    public function __toString(): string
    {
        return $this->currency->symbol() . number_format($this->amount, 2);
    }
}
