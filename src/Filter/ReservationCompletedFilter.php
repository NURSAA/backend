<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Order;
use App\Entity\Restaurant;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class ReservationCompletedFilter extends AbstractFilter
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        ManagerRegistry $managerRegistry,
        LoggerInterface $logger = null,
        array $properties = null,
        NameConverterInterface $nameConverter = null
    ) {
        parent::__construct($managerRegistry, $logger, $properties, $nameConverter);
    }

    protected function filterProperty(
        string $property,
        $value,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        Operation $operation = null,
        array $context = []
    ): void {
        if ($property !== 'completed') {
            return;
        }
        $expr = $this->entityManager->getExpressionBuilder();
        $statuses = $value === true ? ['completed'] : ['created', 'processing'];

        $dql = $this->entityManager->createQueryBuilder()
            ->select('o2.id')
            ->from(Order::class, 'o2')
            ->where('o2.status in (:statuses)')
            ->setParameters([
                'statuses' => $statuses
            ])
            ->getDQL();

        $queryBuilder
            ->join(Restaurant::class, 'restaurant')
            ->join(Order::class, 'order')
            ->andWhere($expr->in('order.id', $dql));
    }

    public function getDescription(string $resourceClass): array
    {
        return [];
    }
}