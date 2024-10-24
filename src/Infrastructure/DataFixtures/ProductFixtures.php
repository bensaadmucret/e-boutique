<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Entity\Product;
use App\Domain\Enum\Currency;
use App\Domain\ValueObject\Money;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $currency = Currency::EUR;
            $price = new Money(mt_rand(10, 100), $currency);
            $product = new Product(
                'Product ' . $i,
                $price,
                'This is the description for product ' . $i,
            );
            $manager->persist($product);
        }

        $manager->flush();
    }
}
