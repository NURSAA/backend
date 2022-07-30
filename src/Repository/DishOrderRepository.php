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

    // /**
    //  * @return DishOrder[] Returns an array of DishOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DishOrder
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
