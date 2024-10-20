<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?Product;

    /**
     * Récupérer tous les produits.
     *
     * @return Product[]
     */
    public function findAllProducts(): array;

    /**
     * Sauvegarder un produit.
     */
    public function save(Product $product): void;

    /**
     * Supprimer un produit.
     */
    public function remove(Product $product): void;
}
