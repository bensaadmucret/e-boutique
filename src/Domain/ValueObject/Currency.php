<?php

namespace App\Domain\ValueObject;

enum Currency: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case JPY = 'JPY';

    public function symbol(): string
    {
        return match($this) {
            self::USD => '$',
            self::EUR => '€',
            self::GBP => '£',
            self::JPY => '¥',
        };
    }
}