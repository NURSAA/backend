<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[ORM\Table(name: '`ingredients`')]
#[ApiResource]
class Ingredient extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['ingredient_group:read', 'menu_section:read', 'order:read'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['ingredient_group:read', 'menu_section:read', 'order:read'])]
    private string $name;

    #[ORM\Column(type: 'integer')]
    #[Groups(['ingredient_group:read', 'menu_section:read', 'order:read'])]
    private int $price;

    #[ORM\ManyToOne(targetEntity: IngredientGroup::class, inversedBy: 'ingredients')]
    #[ORM\JoinColumn(nullable: false)]
    private IngredientGroup $ingredientGroup;

    #[ORM\ManyToMany(targetEntity: Dish::class, mappedBy: 'ingredients')]
    private Collection $dishes;

    public function __construct()
    {
        $this->dishes = new ArrayCollection();
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

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getIngredientGroup(): ?IngredientGroup
    {
        return $this->ingredientGroup;
    }

    public function setIngredientGroup(?IngredientGroup $ingredientGroup): self
    {
        $this->ingredientGroup = $ingredientGroup;

        return $this;
    }

    /**
     * @return Collection<int, Dish>
     */
    public function getDishes(): Collection
    {
        return $this->dishes;
    }

    public function addDish(Dish $dish): self
    {
        if (!$this->dishes->contains($dish)) {
            $this->dishes[] = $dish;
            $dish->addIngredient($this);
        }

        return $this;
    }

    public function removeDish(Dish $dish): self
    {
        if ($this->dishes->removeElement($dish)) {
            $dish->removeIngredient($this);
        }

        return $this;
    }


}
