<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[ORM\Table(name: '`ingredients`')]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'integer')]
    private float $price;

    #[ORM\Column(type: 'object', nullable: true)]
    private object $image;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: IngredientGroup::class)]
    private ingredient $ingredientGroup;

    public function __construct()
    {
        $this->ingredientGroup = new ArrayCollection();
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, IngredientGroup>
     */
    public function getIngredientGroup(): Collection
    {
        return $this->ingredientGroup;
    }

    public function addIngredientGroup(IngredientGroup $ingredientGroup): self
    {
        if (!$this->ingredientGroup->contains($ingredientGroup)) {
            $this->ingredientGroup[] = $ingredientGroup;
            $ingredientGroup->setIngredient($this);
        }

        return $this;
    }

    public function removeIngredientGroup(IngredientGroup $ingredientGroup): self
    {
        if ($this->ingredientGroup->removeElement($ingredientGroup)) {
            // set the owning side to null (unless already changed)
            if ($ingredientGroup->getIngredient() === $this) {
                $ingredientGroup->setIngredient(null);
            }
        }

        return $this;
    }
}
