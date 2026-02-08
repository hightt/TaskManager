<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function login(): Response
    {
        $user = $this->getUser();

        return $this->render('user/dashboard.html.twig', [
            'user' => $user,
        ]);
    }
}