<?php

declare(strict_types=1);

namespace App\Task\Domain\Event;

use DateTimeImmutable;

readonly class TaskStatusUpdatedEvent {
    public function __construct(
        public string $taskId,
        public string $status,
        public string $userId,
        public DateTimeImmutable $occurredAt
    ) {}
}