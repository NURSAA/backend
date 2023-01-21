<?php

namespace App\DataFixtures;

use App\Entity\Menu;
use App\Entity\MenuSection;
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

            $menuSection = $this->createMenuSection($restaurant, $menu);
            $manager->persist($menuSection);
        }

        $manager->flush();
    }

    private function createMenuSection(
        Restaurant $restaurant,
        Menu $menu,
    ): MenuSection {
        $menuSection = new MenuSection();
        $menuSection->setName(sprintf('Menu section - %s', $restaurant->getName()));
        $menuSection->setDescription(sprintf('Menu section description - %s', $restaurant->getName()));
        $menuSection->setSectionOrder(1);
        $menuSection->setMenu($menu);

        return $menuSection;
    }


    public function getDependencies(): array
    {
        return [RestaurantsFixture::class];
    }
}