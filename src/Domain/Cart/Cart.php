<?php

namespace App\Domain\Cart;

use App\Domain\ValueObject\Money;
use App\Domain\ValueObject\Currency;

final class Cart
{
    /** @var CartItem[] */
    private array $items = [];

    public function addItem(CartItem $item): void
    {
        $this->items[] = $item;
    }

    public function removeItem(CartItem $item): void
    {
        foreach ($this->items as $key => $existingItem) {
            if ($existingItem->isEqualTo($item)) {
                unset($this->items[$key]);
                $this->items = array_values($this->items); // Réindexe le tableau.
                break;
            }
        }
    }

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): Money
    {
        if (empty($this->items)) {
            // Par défaut, retournez 0 dans la devise USD si le panier est vide.
            return new Money(0, Currency::USD);
        }

        // On prend la devise du premier élément pour l'ensemble du panier.
        $currency = $this->items[0]->getPrice()->getCurrency();
        $totalAmount = 0.0;

        foreach ($this->items as $item) {
            $totalAmount += $item->getPrice()->getAmount() * $item->getQuantity()->getValue();
        }

        return new Money($totalAmount, $currency);
    }
}
