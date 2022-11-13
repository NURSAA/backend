<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\IngredientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[ORM\Table(name: '`ingredients`')]
#[ApiResource]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('ingredient_group:read')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('ingredient_group:read')]
    private string $name;

    #[ORM\Column(type: 'integer')]
    #[Groups('ingredient_group:read')]
    private int $price;

    #[ORM\ManyToOne(targetEntity: IngredientGroup::class, inversedBy: 'ingredients')]
    #[ORM\JoinColumn(nullable: false)]
    private IngredientGroup $ingredientGroup;

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


}
