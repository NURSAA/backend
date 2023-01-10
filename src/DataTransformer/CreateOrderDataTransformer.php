<?php

namespace App\DataTransformer;

use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Symfony\Routing\IriConverter;
use App\Dto\CreateOrderInput;
use App\Entity\Dish;
use App\Entity\DishOrder;
use App\Entity\Order;
use App\Entity\Payment;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class CreateOrderDataTransformer implements DataTransformerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private IriConverterInterface $iriConverter,
    ) {
    }

    public function transform($data, string $to, array $context = [])
    {
        $order = new Order();
        /** @var Reservation $reservation */
        $reservation = $this->iriConverter->getItemFromIri($data->reservationIri);
        $order->setReservation($reservation);

        $amount = 0;
        foreach ($data->dishOrders as $dishOrderDto) {
            /** @var Dish $dish */
            $dish = $this->iriConverter->getItemFromIri($dishOrderDto->dishIri);
            if ($dish->getMenuSection()->getMenu()->getRestaurant()->getId() !== $reservation->getRestaurant()->getId()) {
                throw new Exception("jeblo sie");
            }

            $dishOrder = new DishOrder();
            $dishOrder->setOrder($order);
            $dishOrder->setDetails($dishOrderDto->details);
            $dishOrder->setPrice($dish->getPrice());
            $dishOrder->setDish($dish);

            $amount += $dish->getPrice();
            $this->entityManager->persist($dishOrder);
        }

        $order->setAmount($amount);

        $this->entityManager->persist($order);

        $payment = new Payment();
        $payment->setOrder($order);
        $payment->setAmount($amount);
        $this->entityManager->persist($payment);

        $order->setPayment($payment);
//        $this->entityManager->flush();
        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Order) {
            return false;
        }

        return Order::class === $to && null !== ($context['input']['class'] ?? null);
    }
}