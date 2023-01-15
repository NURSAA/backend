<?php

namespace App\DataTransformer;

use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Entity\Dish;
use App\Entity\DishOrder;
use App\Entity\Menu;
use App\Entity\Order;
use App\Entity\Payment;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ChangeMenuStatusDataTransformer implements DataTransformerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private IriConverterInterface $iriConverter,
    ) {
    }

    public function transform($data, string $to, array $context = [])
    {
        /** @var Menu $menu */
        $menu = $this->iriConverter->getItemFromIri($data->menuIri);

        $this->entityManager->getRepository(Menu::class)
            ->createQueryBuilder('m')
            ->update()
            ->set('m.status', ':status')
            ->setParameter('status', Menu::STATUS_INACTIVE)
            ->getQuery()->execute();

        $menu->setStatus(Menu::STATUS_ACTIVE);
        return $menu;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Menu) {
            return false;
        }

        return Menu::class === $to && null !== ($context['input']['class'] ?? null);
    }
}