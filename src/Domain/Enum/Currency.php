<?php

namespace App\Domain\Enum;

enum Currency: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case JPY = 'JPY';


    public function code(): string
    {
        return $this->value;
    }

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
