<?php

namespace App\DataFixtures;

use App\Entity\Floor;
use App\Entity\Ingredient;
use App\Entity\IngredientGroup;
use App\Entity\Reservation;
use App\Entity\Restaurant;
use App\Entity\Table;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReservationsFixture extends Fixture implements DependentFixtureInterface
{
    const RESERVATIONS_COUNT = 20;
    public function load(ObjectManager $manager)
    {
        $user = $manager->getRepository(User::class)->findOneBy(['email' => UsersFixture::getMockEmail('user')]);
        if (!$user) {
            return;
        }

        $restaurants = $manager->getRepository(Restaurant::class)->findAll();
        /** @var Restaurant $restaurant */
        foreach ($restaurants as $restaurant) {
            for ($i = 0; $i < ReservationsFixture::RESERVATIONS_COUNT; $i++) {
                $reservation = $this->createReservation($user,  $restaurant);
                $manager->persist($reservation);
            }
        }
        $manager->flush();
    }

    private function createReservation(
        User $user,
        Restaurant $restaurant,
    ): Reservation {
        $reservation = new Reservation();
        $reservation->setUser($user);
        $reservation->setRestaurant($restaurant);
        $reservation->setStart(new \DateTime());
        $reservation->setEnd(new \DateTime());

        return $reservation;
    }


    public function getDependencies(): array
    {
        return [RestaurantsFixture::class];
    }
}