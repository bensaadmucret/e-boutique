<?php

namespace App\Domain\Cart;

use App\Domain\ValueObject\Money;
use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Quantity;

final class CartItem
{
    public function __construct(
    private readonly ProductType $productType,
    private readonly int         $productId,
    private Quantity             $quantity,
    private readonly Money       $price,
    )
    {
    }

    public function getProductType(): ProductType
    {
        return $this->productType;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): Quantity
    {
        return $this->quantity;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function increaseQuantity(int $amount): void
    {
        $this->quantity = $this->quantity->increase($amount);
    }

    public function decreaseQuantity(int $amount): void
    {
        $this->quantity = $this->quantity->decrease($amount);
    }

    public function isEqualTo(CartItem $other): bool
    {
        return $this->productId === $other->productId && $this->productType === $other->productType;
    }

}