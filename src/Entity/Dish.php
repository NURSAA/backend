<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\DishRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DishRepository::class)]
#[ORM\Table(name: '`dishes`')]
#[ApiResource]
class Dish extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $description;

    #[ORM\Column(type: 'File', nullable: true)]
    private File $file;

    #[ORM\OneToMany(mappedBy: 'Ingredient', targetEntity: Ingredient::class)]
    private Ingredient $ingredient;

    #[ORM\Column(type: 'integer')]
    private $order;

    #[ORM\OneToMany(mappedBy: 'MenuSection', targetEntity: MenuSection::class)]
    private Section $section;

    public function __construct()
    {
        $this->ingredient = new ArrayCollection();
        $this->section = new ArrayCollection();
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


    public function getDescription(): string
    {
        return $this->description;
    }


    public function setDescription(string $description): void
    {
        $this->description = $description;
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


    public function getOrder()
    {
        return $this->order;
    }


    public function setOrder($order): void
    {
        $this->order = $order;
    }


    public function getIngredientGroup(): Collection
    {
        return $this->ingredient;
    }

    public function addIngredientGroup(IngredientGroup $ingredient): self
    {
        if (!$this->ingredient->contains($ingredient)) {
            $this->ingredient[] = $ingredient;
            $ingredient->setIngredient($this);
        }

        return $this;
    }

    public function removeIngredientGroup(IngredientGroup $ingredient): self
    {
        if ($this->ingredient->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getIngredient() === $this) {
                $ingredient->setIngredient(null);
            }
        }

        return $this;
    }

    public function getSection(): ArrayCollection|section
    {
        return $this->section;
    }

    public function addSection(IngredientGroup $section): self
    {
        if (!$this->section->contains($section)) {
            $this->section[] = $section;
            $section->setIngredient($this);
        }

        return $this;
    }

    public function removeSection(IngredientGroup $section): self
    {
        if ($this->section->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getIngredient() === $this) {
                $section->setIngredient(null);
            }
        }

        return $this;
    }
}
