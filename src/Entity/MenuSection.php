<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\MenuSectionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuSectionRepository::class)]
#[ORM\Table(name: '`menu_sections`')]
#[ApiResource]
class MenuSection extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $description;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: Menu::class)]
    private Menu $menu;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: MenuSection::class)]
    private MenuSection $parentSection;

    #[ORM\Column(type: 'integer')]
    private $order;

    public function __construct()
    {
        $this->menu = new ArrayCollection();
        $this->parentSection = new ArrayCollection();
    }

    public function getId()
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

    public function getParentSection(): ArrayCollection|parentSection
    {
        return $this->parentSection;
    }

    public function addParentSection(Restaurant $parentSection): self
    {
        if (!$this->parentSection->contains($parentSection)) {
            $this->parentSection[] = $parentSection;
            $parentSection->setIngredient($this);
        }

        return $this;
    }

    public function removeParentSection(Restaurant $parentSection): self
    {
        if ($this->parentSection->removeElement($parentSection)) {
            // set the owning side to null (unless already changed)
            if ($parentSection->getName() === $this) {
                $parentSection->setName(null);
            }
        }

        return $this;
    }

    public function getMenu(): ArrayCollection|menu
    {
        return $this->menu;
    }

    public function addMenu(Restaurant $menu): self
    {
        if (!$this->menu->contains($menu)) {
            $this->menu[] = $menu;
            $menu->setIngredient($this);
        }

        return $this;
    }

    public function removeMenu(Restaurant $menu): self
    {
        if ($this->menu->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getName() === $this) {
                $menu->setName(null);
            }
        }

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
}
