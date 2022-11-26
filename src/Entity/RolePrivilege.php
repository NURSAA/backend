<?php

namespace App\Entity;

use App\Repository\PrivilegeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: '`role_privileges`')]
class RolePrivilege
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $role;

    #[ORM\Column(name: 'entity', type: 'string', length: 255)]
    private string $entity;

    #[ORM\Column(type: 'json')]
    private array $groups = [];

    public function __construct(
        string $role,
        string $entity,
        array $groups
    ) {
        $this->role = $role;
        $this->entity = $entity;
        $this->groups = $groups;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getEntity(): string
    {
        return $this->entity;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }
}
