<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RestaurantsFixture extends Fixture implements DependentFixtureInterface
{
    const RESTAURANTS_COUNT = 10;

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < RestaurantsFixture::RESTAURANTS_COUNT; $i++) {
            $restaurantConfig = $this->getMockRestaurantConfig($i + 1);
            $restaurant = new Restaurant();
            $restaurant->setName($restaurantConfig['name']);
            $restaurant->setDescription($restaurantConfig['description']);
            $restaurant->setUrl($restaurantConfig['url']);
            $this->addUsers($restaurantConfig['users'], $restaurant, $manager);

            $manager->persist($restaurant);
        }

        $manager->flush();
    }

    public function getMockRestaurantConfig(int $index): array
    {
        return [
            'name' => sprintf('Restaurant %s', $index),
            'description' => sprintf('Restaurant %s description', $index),
            'url' => sprintf('www.restaurant-%s.com', $index),
            'users' => [$index == 0 ? 'cook' : '']
        ];
    }

    private function addUsers(
        array $users,
        Restaurant $restaurant,
        ObjectManager $manager
    ): void {
        foreach ($users as $username) {
            $user = $manager->getRepository(User::class)
                ->findOneBy(['email' => UsersFixture::getMockEmail($username)]);

            if ($user) {
                $restaurant->addUser($user);
            }
        }
    }

    public function getDependencies(): array
    {
        return [UsersFixture::class];
    }
}