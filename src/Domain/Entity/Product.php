<?php

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\ValueObject\Money;

#[ORM\Entity(repositoryClass: 'App\Infrastructure\Persistence\Repository\DoctrineProductRepository')]
#[ORM\Table(name: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private readonly int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\Embedded(class: Money::class)]
    private Money $price;

    public function __construct(
        string $name,
        Money $price,
        ?string $description = null
    ) {
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function setPrice(Money $price): void
    {
        $this->price = $price;
    }
}
