<?php

namespace App\DataFixtures;

use App\Entity\Menu;
use App\Entity\Restaurant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MenusFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $restaurants = $manager->getRepository(Restaurant::class)->findAll();
        /** @var Restaurant $restaurant */
        foreach ($restaurants as $restaurant) {
            $menu = new Menu();
            $menu->setName(sprintf('Menu - %s', $restaurant->getName()));
            $menu->setRestaurant($restaurant);
            $menu->setStatus(Menu::STATUS_ACTIVE);

            $manager->persist($menu);
        }

        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [RestaurantsFixture::class];
    }
}