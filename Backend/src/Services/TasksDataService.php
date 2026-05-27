<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ActivityLogRepository;
use App\Repositories\TasksDataRepository;
use App\Services\Interfaces\TasksDataServiceInterface;
use App\Validations\TasksDataValidation;
use DateTime;

class TasksDataService extends BaseService implements TasksDataServiceInterface
{
    private TasksDataRepository $repository;
    private ActivityLogRepository $activeLogs;
    private TasksDataValidation $validation;

    public function __construct(TasksDataRepository $repository, ActivityLogRepository $activeLogs, TasksDataValidation $validation)
    {
        $this->repository = $repository;
        $this->activeLogs = $activeLogs;
        $this->validation = $validation;
    }

    public function setDeadline(string $deadline, int $taskId)
    {
        $errors = [];
        if ($error = $this->validation->emptyDeadline($deadline)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyTaskId($taskId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $newDeadline = new DateTime($deadline);
        $currentCreatedAt = new DateTime('now');
        $this->repository->setDeadlineDateQuery($newDeadline, $taskId);
        $this->activeLogs->createActivityLogQuery('', 'set', 'task data', $taskId, $currentCreatedAt, 21);
        return $this->successResponse();
    }

    
}
