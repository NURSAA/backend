<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DishOrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform;

#[ORM\Entity(repositoryClass: DishOrderRepository::class)]
#[ORM\Table(name: 'dish_orders')]
#[ApiResource]
class DishOrder extends AbstractEntity
{
    const STATUS_CREATED = 'created';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';

    const DISH_ORDER_STATUSES = [
        self::STATUS_CREATED,
        self::STATUS_PROCESSING,
        self::STATUS_COMPLETED,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['order:read'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['order:read'])]
    private string $details;

    #[ORM\Column(type: 'float')]
    #[Groups(['order:read'])]
    private float $price;

    #[ORM\Column(type: 'string')]
    #[Groups(['order:read'])]
    #[Assert\Choice(choices: self::DISH_ORDER_STATUSES, message: 'Choose a valid dish order status.')]
    protected string $status = self::STATUS_CREATED;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'dishOrders')]
    #[ORM\JoinColumn(nullable: false)]
    private Order $order;

    #[ORM\ManyToOne(targetEntity: Dish::class, inversedBy: 'dishOrders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order:read'])]
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

