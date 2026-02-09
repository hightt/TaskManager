<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http;

use App\Task\Domain\TaskRepositoryInterface;
use App\User\Domain\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function login(
        TaskRepositoryInterface $taskRepository,
        UserRepositoryInterface $userRepository,
    ): Response
    {
        $user = $userRepository->findByUserIdentifier($this->getUser()->getUserIdentifier());
        $tasks = $this->isGranted('ROLE_ADMIN') ? $taskRepository->findAll() : $taskRepository->findByUserId($user->userId());

        return $this->render('user/dashboard.html.twig', [
            'user' => $user,
            'tasks' => $tasks
        ]);
    }
}