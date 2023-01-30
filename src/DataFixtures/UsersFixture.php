<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixture extends Fixture
{
    const USER_CONFIG = [
        ['admin', User::ROLE_ADMIN],
        ['user', User::ROLE_USER],
        ['cook', User::ROLE_COOK],
    ];
    const FIXTURE_USER_PASSWORD = 'test';
    const MAIL_SUFFIX = 'test.test';

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager)
    {
        foreach (UsersFixture::USER_CONFIG as $userConfig) {
            $admin = $this->createMockUser($userConfig[0], $userConfig[1]);
            $manager->persist($admin);
        }

        $manager->flush();
    }

    private function createMockUser(string $name, string $role): User
    {
        $user = (new User())
            ->setEmail($this->getMockEmail($name))
            ->setFirstName(sprintf('First %s', $name))
            ->setLastName(sprintf('Last %s', $name))
            ->setPhone('123456')
            ->setRole($role);

        $userPassword = $this->passwordHasher->hashPassword($user, UsersFixture::FIXTURE_USER_PASSWORD);
        $user->setPassword($userPassword);

        return $user;
    }

    public static function getMockEmail(string $name): string
    {
        return sprintf('%s@%s', $name, UsersFixture::MAIL_SUFFIX);
    }
}