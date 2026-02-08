<?php

declare(strict_types=1);

namespace App\Task\Application\CreateTask;

use Throwable;
use Psr\Log\LoggerInterface;
use App\Task\Infrastructure\Doctrine\TaskRepository;
use App\Task\Application\CreateTask\TaskFromCommandFactory;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class TaskCreateHandler
{
    public function __construct(
        private LoggerInterface $logger,
        private TaskFromCommandFactory $taskFromCommandFactory,
        private TaskRepository $taskRepository,
    )
    {}

    public function __invoke(TaskCreateCommand $taskCreateCommand): void
    {
        try {
            $task = $this->taskFromCommandFactory->create($taskCreateCommand);
            $this->taskRepository->save($task);
            $this->taskRepository->flush();
        } catch (Throwable $e) {
            $this->logger->critical($e->getMessage(), ['exception' => $e]);

            throw $e;
        }
    }
}
