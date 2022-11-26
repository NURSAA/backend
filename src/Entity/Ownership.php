<?php

namespace App\Entity;

use App\Repository\PrivilegeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrivilegeRepository::class)]
#[ORM\Table(name: '`ownerships`')]
class Ownership
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $iri;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'privileges')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    public function __construct(
        string $iri,
        User $user
    ) {
        $this->iri = $iri;
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIri(): ?string
    {
        return $this->iri;
    }

    public function setIri(string $iri): self
    {
        $this->iri = $iri;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
