<?php

namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityManager
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function registerUser(
        string $email,
        string $firstName,
        string $lastName,
        string $phone,
        string $password
    ): User | null {
        $user = new User();
        $password = $this->passwordHasher->hashPassword($user, $password);

        $user
            ->setEmail($email)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPhone($phone)
            ->setPassword($password)
            ->setRole(User::ROLE_USER);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

}