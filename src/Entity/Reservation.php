<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use App\Repository\ReservationRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\Table(name: 'reservations')]
#[ApiResource(
    normalizationContext: [
        'groups' => ['reservations:read'],
    ]
)]
#[ApiFilter(NumericFilter::class, properties: ['user.id'])]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['reservations:read'])]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['reservations:read'])]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Restaurant::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['reservations:read'])]
    private Restaurant $restaurant;

    #[ORM\Column(type: 'date')]
    #[Groups(['reservations:read'])]
    private DateTime $start;

    #[ORM\Column(type: 'date')]
    #[Groups(['reservations:read'])]
    private DateTime $end;

    #[ORM\ManyToMany(targetEntity: Table::class, inversedBy: 'reservations')]
    #[Groups(['reservations:read'])]
    private Collection $tables;

    public function __construct()
    {
        $this->tables = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    public function getStart(): DateTime
    {
        return $this->start;
    }

    public function setStart(DateTime $start): void
    {
        $this->start = $start;
    }

    public function getEnd(): DateTime
    {
        return $this->end;
    }

    public function setEnd(DateTime $end): void
    {
        $this->end = $end;
    }

    /**
     * @return Collection<int, Table>
     */
    public function getTables(): Collection
    {
        return $this->tables;
    }

    public function addTable(Table $table): self
    {
        if (!$this->tables->contains($table)) {
            $this->tables[] = $table;
        }

        return $this;
    }

    public function removeTable(Table $table): self
    {
        $this->tables->removeElement($table);

        return $this;
    }

}
