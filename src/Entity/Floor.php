<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FloorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FloorRepository::class)]
#[ORM\Table(name: '`floors`')]
#[ApiResource(
    normalizationContext: [
        'groups' => ['floor:read'],
    ]
)]
class Floor extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['floor:read', 'reservations:read'])]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Restaurant::class, inversedBy: 'floors')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('floor:read')]
    private Restaurant $restaurant;

    #[ORM\Column(type: 'integer')]
    #[Groups(['floor:read', 'reservations:read'])]
    private int $level;

    #[ORM\OneToMany(mappedBy: 'floor', targetEntity: Table::class, orphanRemoval: true)]
    #[Groups('floor:read')]
    private Collection $tables;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['floor:read', 'reservations:read'])]
    private string $name;

    public function __construct()
    {
        $this->tables = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
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
            $table->setFloor($this);
        }

        return $this;
    }

    public function removeTable(Table $table): self
    {
        if ($this->tables->removeElement($table)) {
            // set the owning side to null (unless already changed)
            if ($table->getFloor() === $this) {
                $table->setFloor(null);
            }
        }

        return $this;
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
}
