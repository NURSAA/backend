<?php
namespace App\EventSubscriber;

use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Privilege;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

final class PrivilegeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private IriConverterInterface $iriConverter,
        private EntityManagerInterface $em
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['createOwnershipPrivilege', EventPriorities::POST_WRITE],
        ];
    }

    public function createOwnershipPrivilege(ViewEvent $event): void
    {
        if (Request::METHOD_POST !== $event->getRequest()->getMethod()) {
            return;
        }

        $object = $event->getControllerResult();
        $privilege = new Privilege(
            $this->iriConverter->getIriFromItem($object),
            $this->security->getUser()
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