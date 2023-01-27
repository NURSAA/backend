<?php

namespace App\EventListener;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
    private IriConverterInterface $iriConverter;

    public function __construct(IriConverterInterface $iriConverter)
    {
        $this->iriConverter = $iriConverter;
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event) : void
    {
        $data = $event->getData();
        /** @var User $user */
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $data['@id'] = $this->iriConverter->getIriFromItem($user);
        $data['id'] = $user->getId();
        $data['email'] = $user->getEmail();
        $data['firstName'] = $user->getFirstName();
        $data['lastName'] = $user->getLastName();
        $data['phone'] = $user->getPhone();
        $data['role'] = $user->getRole();
        $data['restaurant'] = null;

        if ($restaurant = $user->getRestaurant()) {
            $data['restaurant'] = $this->iriConverter->getIriFromItem($restaurant);
        }


        $event->setData($data);
    }
}