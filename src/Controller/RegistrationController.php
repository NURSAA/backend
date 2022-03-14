<?php

namespace App\Controller;

use App\Service\SecurityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/register', name: 'api_register')]
class RegistrationController extends AbstractController
{

    #[Route(path: '', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, SecurityManager $securityManager): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        $securityManager->registerUser($content['email'], $content['password']);

        return new JsonResponse();
    }
}