<?php

declare(strict_types=1);

namespace App\Task\Application\CreateTask;

use Ramsey\Uuid\Uuid;
use App\Task\Domain\Task;
use App\Task\Domain\TaskId;
use App\User\Domain\UserId;
use App\User\Domain\UserRepositoryInterface;
use App\Task\Application\CreateTask\TaskCreateCommand;

class TaskFromCommandFactory
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    )
    {}

   public function create(TaskCreateCommand $taskCreateCommand): Task 
{
    $user = $this->userRepository->find(new UserId($taskCreateCommand->userId));
    
    return Task::create(
        new TaskId(Uuid::uuid4()->toString()),
        $taskCreateCommand->name,
        $taskCreateCommand->description,
        $taskCreateCommand->taskStatus,
        $user,
    );
}
}
