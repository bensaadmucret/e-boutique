<?php

namespace App\Infrastructure\Controller;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartItem;
use App\Domain\ValueObject\Money;
use App\Domain\ValueObject\ProductType;
use App\Domain\ValueObject\Quantity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CartController extends AbstractController
{
    private Cart $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    #[Route('/cart/add', name: 'cart_add', methods: ['POST'])]
    public function addItem(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $productId = $data['productId'] ?? null;
        $productType = $data['productType'] ?? null;
        $quantityValue = $data['quantity'] ?? 1;
        $priceValue = $data['price'] ?? 0.0;
        $currency = $data['currency'] ?? 'USD';

        if (!$productId || !$productType) {
            return new JsonResponse(['error' => 'Invalid input'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $productTypeObject = new ProductType($productType);
            $quantity = new Quantity($quantityValue);
            $price = new Money($priceValue, $currency);
            $cartItem = new CartItem($productTypeObject, $productId, $quantity, $price);
            $this->cart->addItem($cartItem);

            return new JsonResponse(['message' => 'Item added to cart successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/cart/remove', name: 'cart_remove', methods: ['DELETE'])]
    public function removeItem(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $productId = $data['productId'] ?? null;
        $productType = $data['productType'] ?? null;

        if (!$productId || !$productType) {
            return new JsonResponse(['error' => 'Invalid input'], Response::HTTP_BAD_REQUEST);
        }

        // Logique pour trouver et retirer l'élément du panier
        $cartItemToRemove = $this->findCartItem($productId, $productType);

        if ($cartItemToRemove) {
            $this->cart->removeItem($cartItemToRemove);
            return new JsonResponse(['message' => 'Item removed from cart successfully'], Response::HTTP_OK);
        }

        return new JsonResponse(['error' => 'Item not found in cart'], Response::HTTP_NOT_FOUND);
    }

    #[Route('/cart/view', name: 'cart_view', methods: ['GET'])]
    public function viewCart(): Response
    {
        $total = $this->cart->getTotal();

        return $this->render('cart/cart_view.html.twig', [
            'cart' => [
                'items' => $this->cart->getItems(),
                'total' => $total,
            ]
        ]);
    }

    private function findCartItem(int $productId, string $productType): ?CartItem
    {
        foreach ($this->cart->getItems() as $item) {
            if ($item->getProductId() === $productId && $item->getProductType()->getType() === $productType) {
                return $item;
            }
        }
        return null;
    }
}