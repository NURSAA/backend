<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Doctrine\HideSoftDeleteInterface;
use App\Dto\SetMenuActiveInput;
use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ORM\Table(name: '`menus`')]
#[ApiResource(
    collectionOperations: [
        'get',
        'set_active' => [
            'method' => "POST",
            'input' => SetMenuActiveInput::class,
            'path' => '/menu/set-active'
        ],
    ],
    itemOperations: [
        'get'
    ],
    normalizationContext: [
        'groups' => ['menu:read'],
    ]
)]
#[ApiFilter(NumericFilter::class, properties: ['restaurant.id'])]
#[ApiFilter(SearchFilter::class, properties: ['status' => 'exact'])]
class Menu extends AbstractEntity implements HideSoftDeleteInterface
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    const MENU_STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
    ];
    
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

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['menu:read'])]
    #[Assert\Choice(choices: self::MENU_STATUSES, message: 'Choose a valid menu status.')]
    protected string $status;

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
    public function getStatus(): string
    {
        return $this->status;
    }
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
