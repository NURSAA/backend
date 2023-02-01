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
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Security;

class ChangeMenuStatusDataTransformer implements DataTransformerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private IriConverterInterface $iriConverter,
        private Security $security,
    ) {
    }

    public function transform($data, string $to, array $context = [])
    {
        /** @var Menu $menu */
        $menu = $this->iriConverter->getItemFromIri($data->menuIri);
        $restaurant = $this->iriConverter->getItemFromIri($data->restaurantIri);

        $this->entityManager->getRepository(Menu::class)
            ->createQueryBuilder('m')
            ->update()
            ->set('m.status', ':status')
            ->andWhere('m.restaurant = :restaurant')
            ->setParameters([
                'status' => Menu::STATUS_INACTIVE,
                'restaurant' => $restaurant->getId(),
            ])
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
        if (!in_array(User::ROLE_ADMIN, $this->security->getUser()->getRoles())) {
            return false;
        }

        return Menu::class === $to && null !== ($context['input']['class'] ?? null);
    }
}