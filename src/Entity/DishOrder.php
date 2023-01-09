<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DishOrderRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DishOrderRepository::class)]
#[ORM\Table(name: 'dish_orders')]
#[ApiResource(
    normalizationContext: [
        'groups' => ['dish_order:read'],
    ]
)]
class DishOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['dish_order:read'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['dish_order:read'])]
    private string $details;

    #[ORM\Column(type: 'float')]
    #[Groups(['dish_order:read'])]
    private float $price;

    #[ORM\Column(type: 'string')]
    #[Groups(['dish_order:read'])]
    private string $status;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'dishOrders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['dish_order:read'])]
    private Order $order;

    #[ORM\ManyToOne(targetEntity: Dish::class, inversedBy: 'dishOrders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['dish_order:read'])]
    private Dish $dish;

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

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getDish(): Dish
    {
        return $this->dish;
    }

    public function setDish(Dish $dish): void
    {
        $this->dish = $dish;
    }
}

