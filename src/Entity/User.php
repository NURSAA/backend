<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\RegistrationController;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: [
        'get',
        'put',
        'register' => [
            'method' => 'POST',
            'path' => '/register',
            'controller' => RegistrationController::class,
            'read' => false,
            'denormalization_context' => ['groups' => ['register']],
        ],
    ],
    denormalizationContext: ['groups' => ['write']],
    normalizationContext: ['groups' => ['read']],
)]
class User extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    const ROLE_SUPER_ADMIN = 'super_admin';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['read', 'register'])]
    private string $email;

    #[ORM\Column(type: 'json')]
    #[Groups(['read', 'write'])]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    #[Groups(['register'])]
    private string $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
 
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // Don't store password in plain text inside User object!
    }
}
