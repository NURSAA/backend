<?php
namespace App\EventSubscriber;

use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Dish;
use App\Entity\DishOrder;
use App\Entity\File;
use App\Entity\Floor;
use App\Entity\Ingredient;
use App\Entity\IngredientGroup;
use App\Entity\Menu;
use App\Entity\MenuSection;
use App\Entity\Ownership;
use App\Entity\Restaurant;
use App\Entity\Table;
use Doctrine\ORM\EntityManagerInterface;
use ProxyManager\ProxyGenerator\AccessInterceptorScopeLocalizer\MethodGenerator\BindProxyProperties;
use ReflectionClass;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

final class PrivilegeSubscriber implements EventSubscriberInterface
{
    private const CREATE_PRIVILEGES = [
        Dish::class,
        Floor::class,
        IngredientGroup::class,
        Ingredient::class,
        MenuSection::class,
        Menu::class,
        Restaurant::class,
        Table::class
    ];

    public function __construct(
        private Security $security,
        private IriConverterInterface $iriConverter,
        private EntityManagerInterface $em
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['createPrivileges', EventPriorities::POST_WRITE],
        ];
    }

    public function createPrivileges(ViewEvent $event)
    {
        $this->createOwnershipPrivilege($event);
//        $this->createPrivilegesOnRegistration($event);
    }

//    private function createPrivilegesOnRegistration(ViewEvent $event): void
//    {
//        if ($event->getRequest()->getRequestUri() !== '/api/register') {
//            return;
//        }
//
//        foreach (self::CREATE_PRIVILEGES as $privilegeClass) {
//            $objects = $this->em->getRepository($privilegeClass)->findAll();
//            foreach ($objects as $object) {
//                $privilege = new Ownership(
//                    $this->iriConverter->getIriFromItem($object),
////                    $user
//                );
//            }
//        }
//
//    }
//
    private function createOwnershipPrivilege(ViewEvent $event): void
    {
        $user = $this->security->getUser();
        if (
            null !== $user
            && Request::METHOD_POST !== $event->getRequest()->getMethod()
        ) {
            return;
        }

        $object = $event->getControllerResult();
        $privilege = new Ownership(
            $this->iriConverter->getIriFromItem($object),
            $user
        );
        $this->em->persist($privilege);
        $this->em->flush();
    }

    public function sendMail(ViewEvent $event): void
    {
//        $book = $event->getControllerResult();
//        $method = $event->getRequest()->getMethod();
//
//        if (!$book instanceof Book || Request::METHOD_POST !== $method) {
//            return;
//        }
//
//        $message = (new Email())
//            ->from('system@example.com')
//            ->to('contact@les-tilleuls.coop')
//            ->subject('A new book has been added')
//            ->text(sprintf('The book #%d has been added.', $book->getId()));
//
//        $this->mailer->send($message);
    }
}