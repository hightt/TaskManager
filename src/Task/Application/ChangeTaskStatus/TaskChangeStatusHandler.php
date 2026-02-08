<?php

declare(strict_types=1);

namespace App\Task\Application\ChangeTaskStatus;

use Throwable;
use App\Task\Domain\TaskId;
use Psr\Log\LoggerInterface;
use App\Task\Infrastructure\Doctrine\TaskRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Task\Application\ChangeTaskStatus\TaskChangeStatusCommand;
use DomainException;

#[AsMessageHandler]
class TaskChangeStatusHandler
{
    public function __construct(
        private LoggerInterface $logger,
        private TaskRepository $taskRepository,
    )
    {}

    public function __invoke(TaskChangeStatusCommand $taskChangeStatusCommand): void
    {
        try {
            $task = $this->taskRepository->find(new TaskId($taskChangeStatusCommand->id));

            if (is_null($task)) {
                throw new DomainException('Task not found');
            }

            $task->changeStatus($taskChangeStatusCommand->status);
            $this->taskRepository->save($task);
            $this->taskRepository->flush();
        } catch (Throwable $e) {
            $this->logger->critical($e->getMessage(), ['exception' => $e]);

            throw $e;
        }
    }
}
