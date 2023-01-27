<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\RegistrationController;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


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
    const ROLE_COOK = 'cook';

    const USER_ORDER_STATUSES = [
        self::ROLE_USER,
        self::ROLE_ADMIN,
        self::ROLE_COOK,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read', 'reservations:read'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['read', 'register', 'reservations:read'])]
    private string $email;

    #[ORM\Column(type: 'string', length: 180)]
    #[Groups(['read', 'write', 'register', 'reservations:read'])]
    private string $firstName;

    #[ORM\Column(type: 'string', length: 180)]
    #[Groups(['read', 'write', 'register', 'reservations:read'])]
    private string $lastName;

    #[ORM\Column(type: 'string', length: 20)]
    #[Groups(['read', 'write', 'register', 'reservations:read'])]
    private string $phone;

    #[ORM\Column(type: 'string')]
    #[Groups(['read', 'write', 'reservations:read'])]
    #[Assert\Choice(choices: self::USER_ORDER_STATUSES, message: 'Choose a valid user role.')]
    private string $role;

    #[ORM\Column(type: 'string')]
    #[Groups(['register'])]
    private string $password;

    #[ORM\ManyToOne(targetEntity: Restaurant::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['read', 'write'])]
    private ?Restaurant $restaurant;

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
    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
 
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

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): void
    {
        $this->restaurant = $restaurant;
    }

    public function eraseCredentials()
    {
        return;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): User
    {
        $this->phone = $phone;
        return $this;
    }
}
