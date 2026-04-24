<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\TasksRepository;
use App\Services\Interfaces\TasksServiceInterface;
use App\Validations\TasksValidation;
use DateTime;

class TasksService implements TasksServiceInterface
{
    private TasksRepository $repository;
    private TasksValidation $validation;

    public function __construct(TasksRepository $repository, TasksValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function createNewTask(string $name, string $createdAt, string $priority)
    {
        $errors = [];
        if ($error = $this->validation->emptyTaskName($name)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyCreatedAt($createdAt)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyPriority($priority)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $newCreatedAt = new DateTime($createdAt);
            $result = $this->repository->createNewTaskQuery($name, $newCreatedAt, $priority);
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

    public function getInProgressTasks(int $userId)
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

    public function getDoneTasks(int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $result = $this->repository->getDoneTasksQuery($userId);
            return [
                'success' => true,
                'data' => $result
            ];
        }
    }
}
