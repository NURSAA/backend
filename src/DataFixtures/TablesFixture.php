<?php

namespace App\DataFixtures;

use App\Entity\Floor;
use App\Entity\Restaurant;
use App\Entity\Table;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TablesFixture extends Fixture implements DependentFixtureInterface
{
    const TABLE_COUNT = 10;
    public function load(ObjectManager $manager)
    {
        $restaurants = $manager->getRepository(Restaurant::class)->findAll();
        /** @var Restaurant $restaurant */
        foreach ($restaurants as $restaurant) {
            $floor = $this->createFloor($restaurant);
            $manager->persist($floor);

            for ($i = 0; $i < TablesFixture::TABLE_COUNT; $i++) {
                $table = $this->createTable($i + 1, $floor,  $restaurant);
                $manager->persist($table);
            }
        }
        $manager->flush();
    }

    private function createFloor(Restaurant $restaurant): Floor {
        $floor = new Floor();
        $floor->setName(sprintf('Floor - %s', $restaurant->getName()));
        $floor->setRestaurant($restaurant);
        $floor->setLevel(1);

        return $floor;
    }

    private function createTable(
        int $index,
        Floor $floor,
        Restaurant $restaurant,
    ): Table {
        $table = new Table();
        $table->setName(sprintf('Table %s - %s', $index, $restaurant->getName()));
        $table->setFloor($floor);
        $table->setSeats($index);

        return $table;
    }


    public function getDependencies(): array
    {
        return [RestaurantsFixture::class];
    }
}