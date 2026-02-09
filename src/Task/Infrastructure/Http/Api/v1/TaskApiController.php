<?php

declare(strict_types=1);

namespace App\Task\Infrastructure\Http\Api\v1;

use App\Task\Application\ApiTask\TaskApiService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/v1/tasks')]
class TaskApiController extends AbstractController
{
    #[Route('/', name: 'app_api_v1_tasks_list', methods: [Request::METHOD_GET])]
    public function list(
        TaskApiService $taskApiService,
    ): JsonResponse
    {
        /* TODO: Authentication */

        $tasks = $taskApiService->getAll();

        return new JsonResponse($tasks);
    }

}