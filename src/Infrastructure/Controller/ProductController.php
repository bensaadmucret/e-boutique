<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Domain\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
    ) {
    }

    #[Route('/product', name: 'list', methods: ['GET'])]
    public function index(): Response
    {
        $products = $this->productRepository->findAllProducts();

        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/{id}', name: 'show_detail', methods: ['GET'])]
    public function detail(int $id): Response
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        return $this->render('product/show_detail.html.twig', [
            'product' => $product,
        ]);
    }
}
