<?php

namespace App\Infrastructure\Controller;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartItem;
use App\Domain\Enum\Currency;
use App\Infrastructure\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CartController extends AbstractController
{

    public function __construct(
        private readonly Cart $cart,
        private readonly CartService $cartService,
    ) {}

    #[Route('/cart/add', name: 'cart_add', methods: ['POST'])]
    public function addItem(Request $request): JsonResponse
    {
        $jsonContent = $request->getContent();
        $data = json_decode($jsonContent, true);
        $token = $data['_csrf_token'] ?? null;

        if (!$this->isCsrfTokenValid('cart_add', $token)) {
            return new JsonResponse(['error' => 'Invalid CSRF token'], JsonResponse::HTTP_FORBIDDEN);
        }

        try {
            $data = $this->cartService->validateJsonContent($request->getContent());
            $currency = Currency::from($data['currency'] ?? 'EUR');
            return $this->cartService->addItem(
                $data['productId'],
                $data['price'] ?? 0.0,
                $currency->code(),
                $data['quantity'] ?? 1,

            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }

    }

    #[Route('/cart/remove', name: 'cart_remove', methods: ['DELETE'])]
    public function removeItem(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $productId = $data['productId'] ?? null;

        if (!$productId) {
            return new JsonResponse(['error' => 'Invalid input'], Response::HTTP_BAD_REQUEST);
        }

        // Logique pour trouver et retirer l'élément du panier
        $cartItemToRemove = $this->findCartItem($productId);

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

    private function findCartItem(int $productId): ?CartItem
    {
        foreach ($this->cart->getItems() as $item) {
            if ($item->getProductId() === $productId) {
                return $item;
            }
        }
        return null;
    }
}