<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`orders`')]
#[ApiResource(
    normalizationContext: [
        'groups' => ['order:read'],
    ]
)]
class Order
{
    const STATUS_CREATED = 'created';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';

    const ORDER_STATUSES = [
        self::STATUS_CREATED,
        self::STATUS_PROCESSING,
        self::STATUS_COMPLETED,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['order:read'])]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Reservation::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order:read'])]
    private Reservation $reservation;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['order:read'])]
    #[Assert\Choice(choices: self::ORDER_STATUSES, message: 'Choose a valid order status.')]
    private string $status;

    #[ORM\OneToOne(inversedBy: 'orders', targetEntity: Payment::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order:read'])]
    private Payment $payment;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: DishOrder::class)]
    #[Groups(['order:read'])]
    private Collection $dishOrders;

    public function __construct()
    {
        $this->dishOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): self
    {
        $this->reservation = $reservation;

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

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return Collection<int, DishOrder>
     */
    public function getDishOrders(): Collection
    {
        return $this->dishOrders;
    }

    public function addDishOrder(DishOrder $dishOrder): self
    {
        if (!$this->dishOrders->contains($dishOrder)) {
            $this->dishOrders[] = $dishOrder;
            $dishOrder->setOrder($this);
        }

        return $this;
    }

    public function removeDishOrder(DishOrder $dishOrder): self
    {
        if ($this->dishOrders->removeElement($dishOrder)) {
            // set the owning side to null (unless already changed)
            if ($dishOrder->getOrder() === $this) {
                $dishOrder->setOrder(null);
            }
        }

        return $this;
    }
}
