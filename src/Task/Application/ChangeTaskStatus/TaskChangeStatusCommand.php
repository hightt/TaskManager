<?php

declare(strict_types=1);

namespace App\Task\Application\ChangeTaskStatus;

use App\Task\Domain\TaskStatus;

class TaskChangeStatusCommand
{
    public function __construct(
        public string $id,
        public TaskStatus $status,
    )
    {
       
    }
}
