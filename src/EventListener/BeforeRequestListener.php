<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;

class BeforeRequestListener
{

    public function __construct(
        private EntityManagerInterface $em
    ) {
    }
    public function onKernelRequest(KernelEvent $event)
    {
        $this->em->getFilters()->enable('hide_soft_deleted_entities');
    }
}