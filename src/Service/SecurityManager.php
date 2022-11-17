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

    public function registerUser(string $email, string $password): User|null
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $email
        ]);
        if ($user instanceof User) {
            throw new \Exception("User already exists");
        }

        $user = new User();
        $password = $this->passwordHasher->hashPassword($user, $password);

        $user
            ->setEmail($email)
            ->setPassword($password)
            ->setRoles([User::ROLE_USER]);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

}