<?php

namespace App\DataFixtures;

use App\Entity\Floor;
use App\Entity\Ingredient;
use App\Entity\IngredientGroup;
use App\Entity\Restaurant;
use App\Entity\Table;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class IngredientsFixture extends Fixture implements DependentFixtureInterface
{
    const INGREDIENTS_COUNT = 10;
    public function load(ObjectManager $manager)
    {
        $restaurants = $manager->getRepository(Restaurant::class)->findAll();
        /** @var Restaurant $restaurant */
        foreach ($restaurants as $restaurant) {
            $ingredientGroup = $this->createIngredientGroup($restaurant);
            $manager->persist($ingredientGroup);

            for ($i = 0; $i < IngredientsFixture::INGREDIENTS_COUNT; $i++) {
                $ingredient = $this->createIngredient($i + 1, $ingredientGroup,  $restaurant);
                $manager->persist($ingredient);
            }
        }
        $manager->flush();
    }

    private function createIngredientGroup(Restaurant $restaurant): IngredientGroup {
        $ingredientGroup = new IngredientGroup();
        $ingredientGroup->setName(sprintf('Ingredient group - %s', $restaurant->getName()));
        $ingredientGroup->setRestaurant($restaurant);

        return $ingredientGroup;
    }

    private function createIngredient(
        int             $index,
        IngredientGroup $ingredientGroup,
        Restaurant      $restaurant,
    ): Ingredient {
        $ingredient = new Ingredient();
        $ingredient->setName(sprintf('Ingredient %s - %s', $index, $restaurant->getName()));
        $ingredient->setIngredientGroup($ingredientGroup);
        $ingredient->setPrice($index * 1000);

        return $ingredient;
    }


    public function getDependencies(): array
    {
        return [RestaurantsFixture::class];
    }
}