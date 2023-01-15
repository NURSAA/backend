<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{
    const USER_CONFIG = [
        ['admin', User::ROLE_ADMIN],
        ['user', User::ROLE_USER],
    ];

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager)
    {
        foreach (UsersFixtures::USER_CONFIG as $userConfig) {
            $admin = $this->createMockUser($userConfig[0], $userConfig[1]);
            $manager->persist($admin);
        }


        $manager->flush();
    }

    private function createMockUser(string $name, string $role): User
    {
        $user = (new User())
            ->setEmail(sprintf('%s@test.test', $name))
            ->setRole($role);

        $adminPassword = $this->passwordHasher->hashPassword($user, 'test');
        $user->setPassword($adminPassword);

        return $user;
    }
}