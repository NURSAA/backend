<?php

namespace App\Repository;

use App\Entity\DishOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DishOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method DishOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method DishOrder[]    findAll()
 * @method DishOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DishOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DishOrder::class);
    }
}
