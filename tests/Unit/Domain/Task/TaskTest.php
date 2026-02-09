<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Task;

use DomainException;
use ReflectionClass;
use App\Task\Domain\Task;
use App\User\Domain\User;
use App\Task\Domain\TaskId;
use App\Task\Domain\TaskStatus;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function test_it_is_created_correctly_via_static_factory(): void
    {
        $taskId = new TaskId('12345678-9012-3456-7890-000000000000');
        $name = 'Test name';
        $description = 'Test description';
        $status = TaskStatus::ACTIVE;

        $userMock = $this->createMock(User::class);
        $task = Task::create($taskId, $name, $description, $status, $userMock);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals($name, $task->name());
        $this->assertEquals($status, $task->status());
        
        $events = $task->pullEvents();
        $this->assertCount(1, $events);
        $this->assertEquals('TaskCreatedEvent', (new ReflectionClass($events[0]))->getShortName());
    }

    public function test_it_changed_status_correctly(): void
    {
        $taskId = new TaskId('12345678-9012-3456-7890-000000000000');
        $name = 'Test name';
        $description = 'Test description';
        $status = TaskStatus::ACTIVE;

        $userMock = $this->createMock(User::class);
        $task = Task::create($taskId, $name, $description, $status, $userMock);

        $task->changeStatus(TaskStatus::IN_PROGRESS);
        
        $this->assertInstanceOf(Task::class, $task);
        $this->assertInstanceOf(TaskId::class, $task->id());
        $this->assertSame(TaskStatus::IN_PROGRESS, $task->status());
        
        $events = $task->pullEvents();
        $this->assertCount(2, $events);
        $this->assertEquals('TaskStatusUpdatedEvent', (new ReflectionClass($events[1]))->getShortName());
    }

    public function test_it_throws_excepton_when_name_is_too_short(): void
    {
        $this->expectException(DomainException::class);
        
        $taskId = new TaskId('12345678-9012-3456-7890-000000000000');
        $name = 'Te';
        $description = 'Test description';
        $status = TaskStatus::ACTIVE;

        $userMock = $this->createMock(User::class);
        $task = Task::create($taskId, $name, $description, $status, $userMock);
    }

    public function test_it_throws_excepton_when_name_is_too_long(): void
    {
        $this->expectException(DomainException::class);
        
        $taskId = new TaskId('12345678-9012-3456-7890-000000000000');
        $name = str_repeat('x', 256);
        $description = 'Test description';
        $status = TaskStatus::ACTIVE;

        $userMock = $this->createMock(User::class);
        $task = Task::create($taskId, $name, $description, $status, $userMock);
    }
}
