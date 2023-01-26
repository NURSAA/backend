<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use App\Doctrine\HideSoftDeleteInterface;
use App\Repository\MenuSectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MenuSectionRepository::class)]
#[ORM\Table(name: '`menu_sections`')]
#[ApiResource(
    normalizationContext: [
        'groups' => ['menu_section:read'],
    ]
)]
#[ApiFilter(NumericFilter::class, properties: ['menu.id'])]
class MenuSection extends AbstractEntity implements HideSoftDeleteInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('menu_section:read')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('menu_section:read')]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('menu_section:read')]
    private string $description;

    #[ORM\Column(type: 'integer')]
    #[Groups('menu_section:read')]
    private int $sectionOrder;

    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'menuSections')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('menu_section:read')]
    private Menu $menu;

    #[ORM\OneToMany(mappedBy: 'menuSection', targetEntity: Dish::class, orphanRemoval: true)]
    #[Groups('menu_section:read')]
    private $dishes;

    public function __construct()
    {
        $this->dishes = new ArrayCollection();
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

    public function getSectionOrder(): int
    {
        return $this->sectionOrder;
    }

    public function setSectionOrder($sectionOrder): void
    {
        $this->sectionOrder = $sectionOrder;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

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
            $dish->setMenuSection($this);
        }

        return $this;
    }

    public function removeDish(Dish $dish): self
    {
        if ($this->dishes->removeElement($dish)) {
            // set the owning side to null (unless already changed)
            if ($dish->getMenuSection() === $this) {
                $dish->setMenuSection(null);
            }
        }

        return $this;
    }

}
