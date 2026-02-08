<?php

declare(strict_types=1);

namespace App\Task\Application\CreateTask;

use App\Task\Domain\TaskStatus;

class TaskCreateCommand
{
    public function __construct(
        public string $name,  
        public string $description,  
        public TaskStatus $taskStatus,
        public string $userId,
    )
    {
       
    }
}
