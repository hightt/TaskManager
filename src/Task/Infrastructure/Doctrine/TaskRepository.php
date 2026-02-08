<?php

declare(strict_types=1);

namespace App\Task\Infrastructure\Doctrine;

use App\Task\Domain\Task;
use App\Task\Domain\TaskId;
use App\Task\Domain\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;
use App\Task\Domain\TaskRepositoryInterface;
use App\Task\Infrastructure\Doctrine\TaskORM;
use App\User\Domain\UserId;
use App\User\Infrastructure\Doctrine\UserORM;
use App\User\Infrastructure\Doctrine\UserRepository;

class TaskRepository implements TaskRepositoryInterface
{
 public function __construct(
        private EntityManagerInterface  $entityManager,
        private UserRepository $userRepository,
    )
    {}

    public function find(TaskId $taskId): ?Task
    {
        $taskOrm = $this->entityManager->find(TaskORM::class, $taskId->id());

        if (is_null($taskOrm)) {
            return null;
        }

        return $this->fromOrm($taskOrm);
    }

    public function save(Task $task): void
    {
        $taskOrm = $this->entityManager->find(TaskORM::class, $task->id()->id());

        if (null === $taskOrm) {
            $taskOrm = new TaskORM();
            $taskOrm->setId($task->id()->id());
            $this->entityManager->persist($taskOrm);
        }

        $taskOrm
            ->setName($task->name())
            ->setDescription($task->description())
            ->setStatus($task->status()->value)
        ;

        $userReference = $this->entityManager->getReference(UserORM::class, $task->user()->userId()->id());
        $taskOrm->setUser($userReference);
    }

    public function toOrm(Task $task)
    {
        $taskOrm = new TaskORM();
        $userReference = $this->entityManager->getReference(UserORM::class, $task->user()->userId()->id());

        return $taskOrm
            ->setId($task->id()->id())
            ->setName($task->name())
            ->setDescription($task->description())
            ->setStatus($task->status()->value)
            ->setUser($userReference)
        ;
    }

    public function fromOrm(TaskORM $taskOrm): Task
    {
        return Task::create(
            new TaskId($taskOrm->getId()),
            $taskOrm->getName(),
            $taskOrm->getDescription(),
            TaskStatus::tryFrom($taskOrm->getStatus()),
            $this->userRepository->fromOrm($taskOrm->getUser())
        );
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function findByUserId(UserId $userId): array
    {
        $tasksOrm = $this
            ->entityManager
            ->getRepository(TaskORM::class)
            ->findBy(['user' => $userId->id()])
        ;

        return array_map(fn(TaskORM $orm) => $this->fromOrm($orm), $tasksOrm);
    }

    public function findAll(): array
    {
        $tasksOrm = $this->entityManager->getRepository(TaskORM::class)->findAll();

        return array_map(
            fn(TaskORM $orm) => $this->fromOrm($orm),
            $tasksOrm
        );
    }
}
