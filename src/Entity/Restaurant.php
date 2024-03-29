<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
#[ORM\Table(name: '`restaurants`')]
#[ApiResource]
class Restaurant extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['menu:read', 'reservations:read'])]
    private int $id;

    #[ORM\Column(type: 'string')]
    #[Groups(['menu:read', 'reservations:read'])]
    private string $name;

    #[ORM\Column(type: 'string')]
    #[Groups(['menu:read', 'reservations:read'])]
    private string $url;

    #[ORM\Column(type: 'string')]
    #[Groups(['menu:read', 'reservations:read'])]
    private string $address;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Floor::class, orphanRemoval: true)]
    #[ApiSubresource]
    #[Groups(['reservations:read'])]
    private Collection $floors;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['menu:read', 'reservations:read'])]
    private string $description;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->floors = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFloors(): Collection
    {
        return $this->floors;
    }

    public function addFloor(Floor $floor): self
    {
        if (!$this->floors->contains($floor)) {
            $this->floors[] = $floor;
            $floor->setRestaurant($this);
        }

        return $this;
    }

    public function removeFloor(Floor $floor): self
    {
        if ($this->floors->removeElement($floor)) {
            if ($floor->getRestaurant() === $this) {
                $floor->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setRestaurant($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            if ($reservation->getRestaurant() === $this) {
                $reservation->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->reservations->contains($user)) {
            $this->users[] = $user;
            $user->setRestaurant($this);
        }

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }
}