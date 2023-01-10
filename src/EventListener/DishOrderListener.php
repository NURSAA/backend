<?php

namespace App\EventListener;

use App\Entity\DishOrder;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class DishOrderListener
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function postUpdate(DishOrder $dishOrder, LifecycleEventArgs $event): void
    {
        foreach ($dishOrder->getOrder()->getDishOrders() as $dishOrder) {
            if ($dishOrder->getStatus() !== DishOrder::STATUS_COMPLETED) {
                return;
            }
        }

        $dishOrder->getOrder()->setStatus(Order::STATUS_COMPLETED);
        $this->entityManager->flush();
    }
}