<?php

declare(strict_types=1);

namespace App\Task\Domain;

use App\User\Domain\User;

class Task
{
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
            throw new \DomainException(sprintf("Task name %s is invalid.", $name));
        }

        return new self($id, $name, $description, $status, $user);
    }

    public function id(): TaskId
    {
        return $this->id;
    }
    
    public function name(): string
    {
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
    }
}
