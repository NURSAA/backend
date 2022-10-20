<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MenuRepository;
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
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['menu:read'])]
    private string $name;

    #[ORM\ManyToOne(targetEntity: Restaurant::class)]
    #[Groups(['menu:read'])]
    private Restaurant $restaurant;

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
}
