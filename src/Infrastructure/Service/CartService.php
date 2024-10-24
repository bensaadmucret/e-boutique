<?php

namespace App\Infrastructure\Service;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartItem;
use App\Domain\Enum\Currency;
use App\Domain\ValueObject\Money;
use App\Domain\ValueObject\Quantity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CartService
{
    public function __construct(
        private readonly Cart $cart,
    ) {}

    public function addItem(
        int $productId,
        float $priceValue,
        string $currency,
        int $quantityValue = 1,

    ): JsonResponse {
        try {
            $currency = Currency::from($currency);
            $quantity = new Quantity($quantityValue);
            $price = new Money($priceValue, $currency);
            $cartItem = new CartItem($productId, $quantity, $price, $currency->code());

            $this->cart->addItem($cartItem);

            return new JsonResponse(['message' => 'Item added to cart successfully'], Response::HTTP_OK);
        } catch (\InvalidArgumentException | \DomainException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function validateJsonContent(string $jsonContent): array|null {
        if (!json_validate($jsonContent, 3)) {
            throw new \InvalidArgumentException('Invalid JSON input');
        }

        return json_decode($jsonContent, true) ?? throw new \RuntimeException('Failed to decode JSON');
    }

    public function getTotal(): Money
    {
        $total = 0.0;
        $currency = null;

        foreach ($this->items as $item) {
            /** @var CartItem $item */
            $itemPrice = $item->getPrice()->getAmount();
            $quantity = $item->getQuantity()->getValue();

            $total += $itemPrice * $quantity;

            // On vérifie la devise (tous les articles doivent avoir la même devise)
            if ($currency === null) {
                $currency = $item->getPrice()->getCurrency();
            } elseif ($currency !== $item->getPrice()->getCurrency()) {
                throw new \DomainException('Different currencies in cart');
            }
        }

        // Retourne le total en tant qu'objet Money
        return new Money($total, $currency);
    }

}
