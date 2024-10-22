<?php

declare(strict_types=1);

namespace App\Domain\Cart;

use App\Domain\Enum\Currency;
use App\Domain\ValueObject\Money;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

final class Cart
{
    /** @var CartItem[] */
    private array $items = [];

    public function __construct(
        private readonly RequestStack $requestStack,
    )
    {
        $this->loadFromSession();
    }

    public function addItem(CartItem $item): void
    {
        $this->items[] = $item;
        $this->saveToSession();
    }

    public function removeItem(CartItem $item): void
    {
        foreach ($this->items as $key => $existingItem) {
            if ($existingItem->isEqualTo($item)) {
                unset($this->items[$key]);
                $this->items = array_values($this->items); // Réindexe le tableau.
                $this->saveToSession();
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

    private function loadFromSession(): void
    {
        $serializedItems = $this->getSession()->get('cart', '');
        if (!empty($serializedItems)) {
            $this->items = unserialize($serializedItems, ['allowed_classes' => [CartItem::class]]) ?: [];
        }
    }

    private function saveToSession(): void
    {
        $this->getSession()->set('cart', serialize($this->items));
    }

    private function getSession(): Session
    {
        return $this->requestStack->getSession();
    }
}
