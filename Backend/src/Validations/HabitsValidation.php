<?php

declare(strict_types=1);

namespace App\Validations;

class HabitsValidation
{
    public function emptyHabitId(int $id)
    {
        if (empty($id)) {
            return 'Habit ID is not exists';
        }
    }
    public function emptyHabitName(string $name)
    {
        if (empty($name)) {
            return 'Name input field is empty';
        }
    }
    public function emptyHabitDescription(string $description)
    {
        if (empty($description)) {
            return 'Description input field is empty';
        }
    }
    public function emptyCreatedAt(string $createdAt)
    {
        if (empty($createdAt)) {
            return 'Created at time is not exists';
        }
    }
    public function emptyUserId(int $userId)
    {
        if (empty($userId)) {
            return 'UserID is not exists';
        }
    }
}
