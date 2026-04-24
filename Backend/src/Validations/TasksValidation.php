<?php

declare(strict_types=1);

namespace App\Validations;

class TasksValidation
{
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
    public function statusAllowed(string $status)
    {
        $allowed = ['todo', 'in progress', 'done'];
        if (!in_array($status, $allowed)) {
            return 'Status is not allowed';
        }
    }
    public function emptyUserId(int $userId)
    {
        if (empty($userId)) {
            return 'UserID is not exists';
        }
    }
}
