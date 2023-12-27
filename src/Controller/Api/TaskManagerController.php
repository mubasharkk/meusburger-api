<?php

namespace App\Controller\Api;

use App\Entity\Task;
use App\Repository\TaskRepository;
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
        $criteria = [
            'status' => $request->query->get('status'),
            'tags'   => $request->query->get('tags'),
        ];

        $tasks = $this->repository->getByFilter(
            array_filter($criteria),
            $request->query->get('limit', 50)
        );

        return $this->json($tasks);
    }

    #[Route('/api/tasks/{id}', name: 'show_api_task_manager', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->json($task);
    }

    #[Route('/api/tasks', name: 'create_api_task_manager', methods: ['POST'])]
    public function store(Request $request, ValidatorInterface $validator)
    {
        $data = $request->request->all();
        $task = $this->repository->createTask(
            $data['title'],
            $data['status'],
            $data['description'] ?? null,
            $data['due_date'],
            is_array($data['tags']) ? $data['tags'] : []
        );

        $errors = $validator->validate($task);

        if ($errors->count()) {
            return (new Response((string)$errors))->setStatusCode(
                Response::HTTP_BAD_REQUEST
            );
        }

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
