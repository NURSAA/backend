<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DishRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DishRepository::class)]
#[ORM\Table(name: '`dishes`')]
#[ApiResource(
    normalizationContext: [
        'groups' => ['dish:read'],
    ]
)]
class Dish extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['dish:read', 'menu_section:read'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['dish:read', 'menu_section:read'])]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['dish:read', 'menu_section:read'])]
    private string $description;

    #[ORM\OneToOne(targetEntity: File::class, cascade: ['persist', 'remove'])]
    #[Groups(['dish:read', 'menu_section:read'])]
    private File $file;

    #[ORM\ManyToOne(targetEntity: MenuSection::class, inversedBy: 'dishes')]
    #[ORM\JoinColumn(nullable: false)]
    private MenuSection $menuSection;

    #[ORM\Column(type: 'integer')]
    #[Groups(['dish:read', 'menu_section:read'])]
    private ?int $dishOrder;

    #[ORM\Column(type: 'integer')]
    #[Groups(['dish:read', 'menu_section:read'])]
    private int $price;

    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'dishes')]
    #[Groups(['dish:read', 'menu_section:read'])]
    private Collection $ingredients;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
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

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getMenuSection(): ?MenuSection
    {
        return $this->menuSection;
    }

    public function setMenuSection(?MenuSection $menuSection): self
    {
        $this->menuSection = $menuSection;

        return $this;
    }

    public function getDishOrder(): ?int
    {
        return $this->dishOrder;
    }

    public function setDishOrder(int $dishOrder): self
    {
        $this->dishOrder = $dishOrder;

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

    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }
}
