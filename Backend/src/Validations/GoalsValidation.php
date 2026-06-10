<?php

declare(strict_types=1);

namespace App\Validations;

class GoalsValidation
{
    public function emptyGoalId(int $id)
    {
        if (empty($id)) {
            return 'Goal ID does not exist';
        }
    }
    public function emptyGoalName(string $name)
    {
        if (empty($name)) {
            return 'Name input field is empty';
        }
    }
    public function emptyGoalDescription(string $description)
    {
        if (empty($description)) {
            return 'Description input field is empty';
        }
    }
    public function emptyStatus(string $status)
    {
        if (empty($status)) {
            return 'Status does not exist';
        }
    }
    public function emptyType(string $type)
    {
        if (empty($type)) {
            return 'Type does not exist';
        }
    }
    public function emptyProgres(string $progres)
    {
        if (empty($progres)) {
            return 'Progres does not exist';
        }
    }
    public function emptyCreatedAt(string $createdAt)
    {
        if (empty($createdAt)) {
            return 'Created at time does not exist';
        }
    }
    public function emptyDeadline(string $deadline)
    {
        if (empty($deadline)) {
            return 'Deadline time does not exist';
        }
    }
    public function emptyUserId(int $userId)
    {
        if (empty($userId)) {
            return 'UserID does not exist';
        }
    }
}
