<?php

declare(strict_types=1);

namespace App\Validations;

class TasksDataValidation
{
    public function emptyTaskDataId(int $id)
    {
        if (empty($id)) {
            return 'Task data ID is not exists';
        }
    }
    public function emptyDeadline(string $deadline)
    {
        if (empty($deadline)) {
            return 'Deadline day is not exists';
        }
    }
    public function emptyTaskId(int $taskId)
    {
        if (empty($taskId)) {
            return 'Task ID is not exists';
        }
    }
}
