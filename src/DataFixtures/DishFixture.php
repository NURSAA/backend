<?php

namespace App\DataFixtures;

use App\Entity\Dish;
use App\Entity\Menu;
use App\Entity\MenuSection;
use App\Entity\Restaurant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DishFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $restaurants = $manager->getRepository(Restaurant::class)->findAll();
        /** @var Restaurant $restaurant */
        foreach ($restaurants as $restaurant) {
            /** @var Menu $menu */
            $menu = $manager->getRepository(Menu::class)->findOneBy(['restaurant'=>$restaurant]);
            if (!$menu) {
                continue;
            }

            $dish = $this->createDish($restaurant, $menu->getMenuSections()[0]);
            $manager->persist($dish);
        }

        $manager->flush();
    }

    private function createDish(
        Restaurant $restaurant,
        MenuSection $menuSection,
    ): Dish {
        $dish = new Dish();
        $dish->setName(sprintf('Dish - %s', $restaurant->getName()));
        $dish->setDescription(sprintf('Dish description - %s', $restaurant->getName()));
        $dish->setPrice(1000);
        $dish->setMenuSection($menuSection);

        return $dish;
    }


    public function getDependencies(): array
    {
        return [
            RestaurantsFixture::class,
            MenusFixture::class
        ];
    }
}