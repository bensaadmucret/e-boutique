<?php

namespace App\Domain\ValueObject;

readonly class ProductType
{
    private string $type;

    private const VALID_TYPES = ['PhysicalProduct', 'DigitalProduct'];

    public function __construct(string $type)
    {
        if (!in_array($type, self::VALID_TYPES)) {
            throw new \InvalidArgumentException("Invalid product type.");
        }

        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }
}