<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ORM\Table(name: '`menus`')]
#[ApiResource(
    normalizationContext: [
        'groups' => ['menu:read'],
    ]
)]
class Menu extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['menu:read'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['menu:read'])]
    private string $name;

    #[ORM\ManyToOne(targetEntity: Restaurant::class)]
    #[Groups(['menu:read'])]
    private Restaurant $restaurant;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuSection::class, orphanRemoval: true)]
    #[Groups(['menu:read'])]
    private Collection $menuSections;

    public function __construct()
    {
        $this->menuSections = new ArrayCollection();
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

    public function getRestaurant(): Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    /**
     * @return Collection<int, MenuSection>
     */
    public function getMenuSections(): Collection
    {
        return $this->menuSections;
    }

    public function addMenuSection(MenuSection $menuSection): self
    {
        if (!$this->menuSections->contains($menuSection)) {
            $this->menuSections[] = $menuSection;
            $menuSection->setMenu($this);
        }

        return $this;
    }

    public function removeMenuSection(MenuSection $menuSection): self
    {
        if ($this->menuSections->removeElement($menuSection)) {
            // set the owning side to null (unless already changed)
            if ($menuSection->getMenu() === $this) {
                $menuSection->setMenu(null);
            }
        }

        return $this;
    }
}
