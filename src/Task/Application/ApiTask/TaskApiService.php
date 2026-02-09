<?php

declare(strict_types=1);

namespace App\Task\Application\ApiTask;

use App\Task\Domain\TaskRepositoryInterface;

class TaskApiService
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    )
    {}

    public function getAll(): array
    {
        $tasks = $this->taskRepository->findAll();
        $data = array_map(function ($task) {
            return [
                'id' => $task->id()->id(),
                'name' => $task->name(),
                'description' => $task->description(),
                'status' => $task->status()->value, 
                'user' => [
                    'id' => $task->user()->userId()->id(),
                    'username' => $task->user()->username(),
                    'email' => $task->user()->userEmail()->value(),
                ],
            ];
        }, $tasks);

        return $data;
    }
}
