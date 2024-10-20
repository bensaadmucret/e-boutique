<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Repository\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\Entity\Product;

class DoctrineProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findById(int $id): ?Product
    {
        return $this->find($id);
    }

    public function findAllProducts(): array
    {
        return $this->findAll();
    }

    public function save(Product $product): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($product);
        $entityManager->flush();
    }

    public function remove(Product $product): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($product);
        $entityManager->flush();
    }
}
