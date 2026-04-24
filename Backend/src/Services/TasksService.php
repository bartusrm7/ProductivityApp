<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\TasksRepository;
use App\Services\Interfaces\TasksServiceInterface;
use App\Validations\TasksValidation;

class TasksService implements TasksServiceInterface
{
    private TasksRepository $repository;
    private TasksValidation $validation;

    public function __construct(TasksRepository $repository, TasksValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function createNewTask(string $name)
    {
        $errors = [];
        if ($error = $this->validation->emptyTaskName($name)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $result = $this->repository->createNewTaskQuery($name);
            return [
                'success' => true,
                'data' => $result
            ];
        }
    }

    public function getToDoTasks(int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $result = $this->repository->getToDoTasksQuery($userId);
            return [
                'success' => true,
                'data' => $result
            ];
        }
    }

    public function getInProgressTasks($userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $result = $this->repository->getInProgressTasksQuery($userId);
            return [
                'success' => true,
                'data' => $result
            ];
        }
    }
}
