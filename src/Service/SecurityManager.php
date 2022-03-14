<?php

namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityManager
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function registerUser(string $email, string $password): string
    {
        $user = new User();
        $password = $this->passwordHasher->hashPassword($user, $password);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRoles([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN]);

        dump($user);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $password;
    }

}