<?php

namespace App\Repository;

use App\Entity\MenuSection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MenuSection|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuSection|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuSection[]    findAll()
 * @method MenuSection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuSectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuSection::class);
    }
}
