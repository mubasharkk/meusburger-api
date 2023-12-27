<?php

namespace App\Controller\Api;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Requests\CreateTaskRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskManagerController extends AbstractController
{

    /**
     * @var \App\Repository\TaskRepository
     */
    private TaskRepository $repository;

    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/api/tasks', name: 'list_api_task_manager', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $data = $request->query->all();

        $tasks = $this->repository->getByFilter(
            $data['search'] ?? null,
            $data['status'] ?? null,
            $data['limit'] ?? 5,
            $data['page'] ?? 1,
        );

        return $this->json($tasks);
    }

    #[Route('/api/tasks/{id}', name: 'show_api_task_manager', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->json($task);
    }

    #[Route('/api/tasks', name: 'create_api_task_manager', methods: ['POST'])]
    public function store(
        CreateTaskRequest $request,
        ValidatorInterface $validator
    ) {
        $errors = $request->validate();
        if (count($errors['errors'])) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $task = $this->repository->createTask(
            $request->title,
            $request->status,
            $request->description,
            $request->due_date,
            $request->tags
        );

        $this->repository->save($task);

        return $this->json($task);
    }

    #[Route('/api/tasks/{id}', name: 'delete_api_task_manager', methods: ['DELETE'])]
    public function destroy(Task $task)
    {
        $this->repository->delete($task);
        $this->json([]);
    }

}
