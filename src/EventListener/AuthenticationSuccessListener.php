<?php

namespace App\EventListener;

use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\Bridge\Symfony\Routing\IriConverter;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
    private IriConverterInterface $iriConverter;

    public function __construct(IriConverterInterface $iriConverter)
    {
        $this->iriConverter = $iriConverter;
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $data['@id'] = $this->iriConverter->getIriFromItem($user);
        $data['id'] = $user->getId();
        $data['email'] = $user->getEmail();
        $data['role'] = $user->getRole();
        $data['restaurant'] = null;

        if ($restaurant = $user->getRestaurant()) {
            $data['restaurant'] = $this->iriConverter->getIriFromItem($restaurant);
        }


        $event->setData($data);
    }
}