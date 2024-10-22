<?php

namespace App\Domain\ValueObject;

readonly class ProductType
{

    private const VALID_TYPES = ['PhysicalProduct', 'DigitalProduct'];

    public function __construct(private string $type)
    {
        if (!in_array($type, self::VALID_TYPES)) {
            throw new \InvalidArgumentException("Invalid products type.");
        }

    }

    public function getType(): string
    {
        return $this->type;
    }
}