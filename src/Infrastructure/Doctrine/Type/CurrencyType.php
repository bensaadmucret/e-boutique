<?php

namespace App\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class CurrencyType extends Type
{
    const NAME = 'currency';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'VARCHAR(3)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Currency
    {
        return new Currency($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return (string) $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
