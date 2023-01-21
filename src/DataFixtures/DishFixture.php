<?php

namespace App\DataFixtures;

use App\Entity\Dish;
use App\Entity\Ingredient;
use App\Entity\IngredientGroup;
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
            $menu = $manager->getRepository(Menu::class)
                ->findOneBy(['restaurant'=>$restaurant]);
            if (!$menu) {
                continue;
            }

            /** @var IngredientGroup $ingredientGroup */
            $ingredientGroup = $manager->getRepository(IngredientGroup::class)
                ->findOneBy(['restaurant'=>$restaurant]);
            if (!$ingredientGroup) {
                continue;
            }

            $dish = $this->createDish($restaurant, $menu->getMenuSections()[0], $ingredientGroup);
            $manager->persist($dish);
        }

        $manager->flush();
    }

    private function createDish(
        Restaurant $restaurant,
        MenuSection $menuSection,
        IngredientGroup $ingredientGroup,
    ): Dish {
        $dish = new Dish();
        $dish->setName(sprintf('Dish - %s', $restaurant->getName()));
        $dish->setDescription(sprintf('Dish description - %s', $restaurant->getName()));
        $dish->setPrice(1000);
        $dish->setMenuSection($menuSection);

        $ingredients = $ingredientGroup->getIngredients();
        $dish->addIngredient($ingredients[0]);
        $dish->addIngredient($ingredients[1]);
        $dish->addIngredient($ingredients[3]);

        return $dish;
    }


    public function getDependencies(): array
    {
        return [
            RestaurantsFixture::class,
            IngredientsFixture::class,
            MenusFixture::class
        ];
    }
}