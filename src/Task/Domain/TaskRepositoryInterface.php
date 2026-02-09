<?php

declare(strict_types=1);

namespace App\Task\Domain;

use App\Task\Domain\Task;
use App\Task\Domain\TaskId;
use App\User\Domain\UserId;

interface TaskRepositoryInterface
{
    public function find(TaskId $id): ?Task;

    /** @return Task[] */
    public function findAll(): array;

    public function save(Task $task): void;

    public function flush(): void;

    /** @return Task[] */
    public function findByUserId(UserId $userId): array;
}

