<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use App\Repository\IngredientGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IngredientGroupRepository::class)]
#[ORM\Table(name: '`ingredient_groups`')]
#[ApiResource(
    normalizationContext: [
        'groups' => ['ingredient_group:read'],
    ]
)]
#[ApiFilter(NumericFilter::class, properties: ['restaurant.id'])]
class IngredientGroup extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('ingredient_group:read')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('ingredient_group:read')]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'ingredientGroup', targetEntity: Ingredient::class, orphanRemoval: true)]
    #[Groups('ingredient_group:read')]
    private Collection $ingredients;

    #[ORM\ManyToOne(targetEntity: Restaurant::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('ingredient_group:read')]
    private Restaurant $restaurant;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
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

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setIngredientGroup($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getIngredientGroup() === $this) {
                $ingredient->setIngredientGroup(null);
            }
        }

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


}
