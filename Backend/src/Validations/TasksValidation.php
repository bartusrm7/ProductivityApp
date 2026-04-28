<?php

declare(strict_types=1);

namespace App\Validations;

class TasksValidation
{
    public function emptyTaskId(int $id)
    {
        if (empty($id)) {
            return 'Task ID is not exists';
        }
    }
    public function emptyTaskName(string $name)
    {
        if (empty($name)) {
            return 'Name input field is empty';
        }
    }
    public function emptyCreatedAt(string $createdAt)
    {
        if (empty($createdAt)) {
            return 'Created at time is not exists';
        }
    }
    public function emptyPriority(string $priority)
    {
        if (empty($priority)) {
            return 'Priority input field is empty';
        }
    }
    public function emptyTaskStatus(string $status)
    {
        if (empty($status)) {
            return 'Status input field is empty';
        }
    }
    public function emptyUserId(int $userId)
    {
        if (empty($userId)) {
            return 'UserID is not exists';
        }
    }
}
