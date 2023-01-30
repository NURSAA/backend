<?php

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Order;
use App\Entity\Reservation;
use App\Entity\Restaurant;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

class OrdersInProgressOnly implements QueryCollectionExtensionInterface,
    QueryItemExtensionInterface
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        Operation $operation = null,
        array $context = []
    ): void {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        Operation $operation = null,
        array $context = []
    ): void {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        if (Reservation::class !== $resourceClass
            || !in_array(User::ROLE_COOK, $this->security->getUser()->getRoles())
        ) {
            return;
        }

        $expr = $this->entityManager->getExpressionBuilder();
        $statuses = ['created', 'processing'];

        $dql = $this->entityManager->createQueryBuilder()
            ->select('o2.id')
            ->from(Order::class, 'o2')
            ->where('o2.status in (:statuses)')
            ->andWhere(sprintf('o2.reservation = %s.id', $queryBuilder->getRootAliases()[0]))
            ->getDQL();

        $queryBuilder
            ->join(Restaurant::class, 'restaurant')
            ->join(Order::class, 'ord')
            ->andWhere($expr->in('ord.id', $dql))
            ->setParameter('statuses', $statuses);
    }
}
