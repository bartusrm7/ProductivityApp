<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ActivityLogRepository;
use App\Repositories\TasksDataRepository;
use App\Repositories\TasksRepository;
use App\Services\Interfaces\TasksServiceInterface;
use App\Validations\TasksValidation;
use DateTime;

class TasksService extends BaseService implements TasksServiceInterface
{
    private TasksRepository $repository;
    private TasksDataRepository $taskDataRepo;
    private ActivityLogRepository $activeLogs;
    private TasksValidation $validation;

    public function __construct(TasksRepository $repository, TasksDataRepository $taskDataRepo, ActivityLogRepository $activeLogs, TasksValidation $validation)
    {
        $this->repository = $repository;
        $this->taskDataRepo = $taskDataRepo;
        $this->activeLogs = $activeLogs;
        $this->validation = $validation;
    }

    public function createNewTask(string $name, string $createdAt, string $priority, int $userId)
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
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $newCreatedAt = new DateTime($createdAt)->modify('+2 hours');
            $currentCreatedAt = new DateTime('now');
            $result =  $this->repository->createNewTaskQuery($name, $newCreatedAt, $priority, $userId);
            $taskId = $result->getId();
            $this->activeLogs->createActivityLogQuery($name, 'create', 'task', $taskId, $currentCreatedAt, $userId);
            return $this->successResponse();
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
            return $this->successResponseWithData($result);
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
            return $this->successResponseWithData($result);
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
            return $this->successResponseWithData($result);
        }
    }

    public function getFailedTasks(int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $result = $this->repository->getFailedTasksQuery($userId);
            return $this->successResponseWithData($result);
        }
    }

    public function doneTask(int $id, string $status, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyTaskId($id)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyTaskStatus($status)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $currentCreatedAt = new DateTime('now');
            $this->repository->doneTaskQuery($id, $status, $userId);
            $this->activeLogs->createActivityLogQuery('', 'done', 'task', $id, $currentCreatedAt, $userId);
            return $this->successResponse();
        }
    }

    public function editTask(int $id, string $name, string $priority, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyTaskId($id)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyTaskName($name)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyPriority($priority)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $currentCreatedAt = new DateTime('now');
            $this->repository->editTaskQuery($id, $name, $priority, $userId);
            $this->activeLogs->createActivityLogQuery($name, 'edit', 'task', $id, $currentCreatedAt, $userId);
            return $this->successResponse();
        }
    }

    public function deleteTask(int $id, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyTaskId($id)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $currentCreatedAt = new DateTime('now');
            $this->repository->deleteTaskQuery($id, $userId);
            $this->activeLogs->createActivityLogQuery('', 'delete', 'task', $id, $currentCreatedAt, $userId);
            return $this->successResponse();
        }
    }

    public function sortTasks(array $params, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyTaskStatus($params['status'])) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $result = $this->repository->sortDataByStatusAndTitleQuery($params);
            return $this->successResponseWithData($result);
        }
    }

    public function getTodayTasks(string $status, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyTaskStatus($status)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $result = $this->repository->getTodayTasksQuery($status, $userId);
            return $this->successResponseWithData($result);
        }
    }

    public function taskFailed(int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $this->repository->updateTaskFailedQuery($userId);
        return $this->successResponse();
    }
}
