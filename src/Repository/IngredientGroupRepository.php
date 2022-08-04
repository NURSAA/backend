<?php

namespace App\Repository;

use App\Entity\IngredientGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IngredientGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method IngredientGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method IngredientGroup[]    findAll()
 * @method IngredientGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IngredientGroup::class);
    }
}
