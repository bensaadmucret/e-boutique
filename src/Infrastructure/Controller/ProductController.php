<?php

namespace App\Infrastructure\Controller;

use App\Domain\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
    ) {
    }

    #[Route('/products', name: 'products', methods: ['GET'])]
    public function index(): Response
    {
        $products = $this->productRepository->findAllProducts();

        return $this->render('product/products.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/{id}', name: 'product_detail', methods: ['GET'])]
    public function detail(int $id): Response
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        return $this->render('product/product_detail.html.twig', [
            'product' => $product,
        ]);
    }
}
