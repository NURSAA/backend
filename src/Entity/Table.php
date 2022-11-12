<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TableRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TableRepository::class)]
#[ORM\Table(name: '`tables`')]
#[ApiResource]
class Table
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('floor:read')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('floor:read')]
    private string $name;

    #[ORM\Column(type: 'integer')]
    #[Groups('floor:read')]
    private int $seats;

    #[ORM\ManyToOne(targetEntity: Floor::class, inversedBy: 'tables')]
    #[ORM\JoinColumn(nullable: false)]
    private Floor $floor;

    #[ORM\ManyToOne(targetEntity: Reservation::class, inversedBy: 'tables')]
    #[ORM\JoinColumn(nullable: false)]
    private Reservation $reservation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSeats(): ?int
    {
        return $this->seats;
    }

    public function setSeats(int $seats): self
    {
        $this->seats = $seats;
        return $this;
    }

    public function getFloor(): ?Floor
    {
        return $this->floor;
    }

    public function setFloor(?Floor $floor): self
    {
        $this->floor = $floor;
        return $this;
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
}
