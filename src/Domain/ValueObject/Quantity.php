<?php

namespace App\Domain\ValueObject;

readonly class Quantity
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 1) {
            throw new \InvalidArgumentException("Quantity must be at least 1.");
        }

        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function increase(int $amount): self
    {
        return new self($this->value + $amount);
    }

    public function decrease(int $amount): self
    {
        $newValue = $this->value - $amount;

        if ($newValue < 1) {
            throw new \InvalidArgumentException("Quantity cannot be less than 1.");
        }

        return new self($newValue);
    }
}
