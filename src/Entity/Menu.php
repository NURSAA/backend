<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\MenuRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ORM\Table(name: '`menus`')]
#[ApiResource]
class Menu extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: Restaurant::class)]
    private Restaurant $restaurant;


    public function __construct()
    {
        $this->restaurant = new ArrayCollection();
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

    public function getRestaurants(): Collection
    {
        return $this->restaurant;
    }

    public function addRestaurants(Restaurant $restaurant): self
    {
        if (!$this->$restaurant->contains($restaurant)) {
            $this->$restaurant[] = $restaurant;
            $restaurant->setIngredient($this);
        }

        return $this;
    }

    public function removeRestaurants(Restaurant $restaurant): self
    {
        if ($this->restaurant->removeElement($restaurant)) {
            // set the owning side to null (unless already changed)
            if ($restaurant->getName() === $this) {
                $restaurant->setName(null);
            }
        }

        return $this;
    }
}
