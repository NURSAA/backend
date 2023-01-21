<?php

namespace App\DataFixtures;

use App\Entity\Dish;
use App\Entity\DishOrder;
use App\Entity\Floor;
use App\Entity\Ingredient;
use App\Entity\IngredientGroup;
use App\Entity\Menu;
use App\Entity\Order;
use App\Entity\Payment;
use App\Entity\Reservation;
use App\Entity\Restaurant;
use App\Entity\Table;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrdersFixture extends Fixture implements DependentFixtureInterface
{
    const ORDERS_COUNT = 5;
    const DISH_ORDERS_COUNT = 5;
    public function load(ObjectManager $manager)
    {
        $user = $manager->getRepository(User::class)->findOneBy(['email' => UsersFixture::getMockEmail('user')]);
        if (!$user) {
            return;
        }

        $reservations = $manager->getRepository(Reservation::class)->findAll();
        /** @var Reservation $reservation */
        foreach ($reservations as $reservation) {
            for ($i = 0; $i < OrdersFixture::ORDERS_COUNT; $i++) {
                $amount = $i * 10.5;

                $payment = $this->createPayment($amount);
                $manager->persist($payment);

                $order = $this->createOrder($reservation, $amount, $payment);
                $manager->persist($order);



                /** @var Menu $menu */
                $menu = $manager->getRepository(Menu::class)->findOneBy(['restaurant' => $reservation->getRestaurant()]);
                $firstDish = $menu->getMenuSections()[0]->getDishes()[0];
                $this->addDishOrders(
                    $order,
                    $firstDish,
                    $manager
                );
            }
        }
        $manager->flush();
    }

    private function createPayment(
        float $amount,
    ): Payment {
        $payment = new Payment();
        $payment->setStatus(Payment::STATUS_PENDING);
        $payment->setAmount($amount);

        return $payment;
    }

    private function createOrder(
        Reservation $reservation,
        float $amount,
        Payment $payment,
    ): Order {
        $order = new Order();
        $order->setReservation($reservation);
        $order->setStatus(Order::STATUS_CREATED);
        $order->setPayment($payment);
        $order->setAmount($amount);

        return $order;
    }

    private function addDishOrders(
        Order $order,
        Dish $dish,
        ObjectManager $manager,
    ): void {
        for ($i = 0; $i < OrdersFixture::DISH_ORDERS_COUNT; $i++) {
            $dishOrder = new DishOrder();
            $dishOrder->setStatus(DishOrder::STATUS_CREATED);
            $dishOrder->setDetails(sprintf('Dish order - %s', $i));
            $dishOrder->setStatus(DishOrder::STATUS_CREATED);
            $dishOrder->setPrice($i * 1000);
            $dishOrder->setDish($dish);
            $manager->persist($dishOrder);

            $order->addDishOrder($dishOrder);
        }
    }


    public function getDependencies(): array
    {
        return [ReservationsFixture::class];
    }
}