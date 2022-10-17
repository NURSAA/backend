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
#[ORM\Table(name: '`restaurant`')]
#[ApiResource]
class Restaurant extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['menu:read'])]
    private int $id;

    #[ORM\Column(type: 'string')]
    #[Groups(['menu:read'])]
    private string $name;

    #[ORM\Column(type: 'string')]
    #[Groups(['menu:read'])]
    private string $url;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Floor::class, orphanRemoval: true)]
    #[ApiSubresource]
    private Collection $floors;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['menu:read'])]
    private string $description;

    public function __construct()
    {
        $this->floors = new ArrayCollection();
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

    /**
     * @return Collection<int, Floor>
     */
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
            // set the owning side to null (unless already changed)
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

}