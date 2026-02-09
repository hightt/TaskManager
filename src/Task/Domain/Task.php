<?php

declare(strict_types=1);

namespace App\Task\Domain;

use DomainException;
use DateTimeImmutable;
use App\User\Domain\User;
use App\Task\Domain\Event\TaskCreatedEvent;
use App\Task\Domain\Event\TaskStatusUpdatedEvent;

class Task
{
    /** object[] $recordedEvents */
    private array $recordedEvents = [];

    public function __construct(
      private TaskId $id,  
      private string $name,  
      private string $description,  
      private TaskStatus $status,
      private User $user,
    ){}

    public static function create(
        TaskId $id,  
        string $name,  
        string $description,  
        TaskStatus $status,
        User $user,
    )
    {
        if (strlen($name) < 3 || strlen($name) > 255) {
            throw new DomainException(sprintf("Task name %s is invalid.", $name));
        }

        $task = new self($id, $name, $description, $status, $user);

        $task->record(new TaskCreatedEvent(
            $id->id(),
            $status->value,
            $user->userId()->id(),
            new DateTimeImmutable()
        ));

        return $task;
    }

    public function id(): TaskId
    {
        return $this->id;
    }
    
    public function name(): string
    {
        /* TODO: VALIDATION */
        
        return $this->name;
    }


    public function description(): string
    {
        return $this->description;
    }

    public function status(): TaskStatus
    {
        return $this->status;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function changeStatus(TaskStatus $newStatus): void 
    {
        $this->status = $newStatus;

        $this->record(new TaskStatusUpdatedEvent(
            $this->id->id(),
            $newStatus->value,
            $this->user()->userId()->id(),
            new DateTimeImmutable()
        ));
    }

    private function record(object $event): void 
    {
        $this->recordedEvents[] = $event;
    }

    public function pullEvents(): array 
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = []; 

        return $events;
    }
}
