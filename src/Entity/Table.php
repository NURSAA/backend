<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use App\Repository\TableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TableRepository::class)]
#[ORM\Table(name: '`tables`')]
#[ApiResource]
#[ApiFilter(NumericFilter::class, properties: ['floor.restaurant.id'])]
class Table
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['floor:read', 'reservations:read'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['floor:read', 'reservations:read'])]
    private string $name;

    #[ORM\Column(type: 'integer')]
    #[Groups(['floor:read', 'reservations:read'])]
    private int $seats;

    #[ORM\ManyToOne(targetEntity: Floor::class, inversedBy: 'tables')]
    #[Groups(['reservations:read'])]
    #[ORM\JoinColumn(nullable: false)]
    private Floor $floor;

    #[ORM\ManyToMany(targetEntity: Reservation::class, mappedBy: 'tables')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

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

    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->addTable($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            $reservation->removeTable($this);
        }

        return $this;
    }
}
