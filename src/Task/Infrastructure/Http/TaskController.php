<?php

declare(strict_types=1);

namespace App\Task\Infrastructure\Http;

use App\Task\Domain\TaskId;
use App\Task\Infrastructure\Form\TaskType;
use App\Task\Domain\TaskRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Task\Application\CreateTask\TaskCreateCommand;
use App\Task\Infrastructure\Form\TaskChangeStatusType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Task\Application\ChangeTaskStatus\TaskChangeStatusCommand;

class TaskController extends AbstractController
{
    #[Route('/task/new', name: 'app_task_new')]
    public function new(
        Request $request, 
        MessageBusInterface $bus
    ): Response
    {
        $form = $this->createForm(TaskType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $taskCreateCommand = new TaskCreateCommand(
                $data['name'],
                $data['description'],
                $data['status'],
                $data['user']->getId(),
            );

            $bus->dispatch($taskCreateCommand);

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('task/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /* TODO: User authorization */
    #[Route('/task/change_status/{id}', name: 'app_task_change_status')]
    public function changeStatus(
        Request $request,
        TaskRepositoryInterface $taskRepository,
        MessageBusInterface $bus,
        string $id,
    ): Response
    {
        $task = $taskRepository->find(new TaskId($id));
        $taskChangeStatusCommand = new TaskChangeStatusCommand($task->id()->id(), $task->status());
        $form = $this->createForm(TaskChangeStatusType::class, $taskChangeStatusCommand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $bus->dispatch($taskChangeStatusCommand);

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('task/change_status.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}