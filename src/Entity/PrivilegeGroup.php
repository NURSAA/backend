<?php

namespace App\Entity;

use App\Repository\PrivilegeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'privilege_groups')]
class PrivilegeGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Privilege::class, inversedBy: 'privilegeGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private Privilege $privilege;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'privileges')]
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    public function getId(): string
    {
        return $this->id;
    }

    public function getPrivilege(): Privilege
    {
        return $this->privilege;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
