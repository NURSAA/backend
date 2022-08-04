<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DishOrderRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

#[ORM\Entity(repositoryClass: DishOrderRepository::class)]
#[ORM\Table(name: 'dish_orders')]
#[ApiResource]
class DishOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $details;

    #[ORM\Column(type: 'float')]
    private float $price;

    #[ORM\Column(type: 'string')]
    private string $status;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'dishOrders')]
    #[ORM\JoinColumn(nullable: false)]
    private Order $orders;

    #[ORM\ManyToOne(targetEntity: Dish::class, inversedBy: 'dishOrders')]
    #[ORM\JoinColumn(nullable: false)]
    private Dish $dishes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getOrders(): ?Order
    {
        return $this->orders;
    }

    public function setOrders(?Order $orders): self
    {
        $this->orders = $orders;

        return $this;
    }

    public function getDishes(): Dish
    {
        return $this->dishes;
    }

    public function setDishes(Dish $dishes): void
    {
        $this->dishes = $dishes;
    }
}

