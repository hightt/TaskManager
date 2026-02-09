<?php

declare(strict_types=1);

namespace App\Task\Domain\Event;

use DateTimeImmutable;

readonly class TaskCreatedEvent {
    public function __construct(
        public string $taskId,
        public string $name,
        public string $userId,
        public DateTimeImmutable $occurredAt
    ) {}
}