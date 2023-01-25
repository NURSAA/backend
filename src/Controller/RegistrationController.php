<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\SecurityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class RegistrationController extends AbstractController
{
    private SecurityManager $securityManager;

    public function __construct(SecurityManager $securityManager)
    {
        $this->securityManager = $securityManager;
    }

    public function __invoke(Request $request): User
    {
        $content = json_decode($request->getContent(), true);
        return $this->securityManager->registerUser(
            $content['email'],
            $content['firstName'],
            $content['lastName'],
            $content['phone'],
            $content['password']
        );
    }
}