<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

interface GoalsServiceInterface
{
    public function createGoal(string $name, string $type, string $createdAt, int $userId);
    public function doneGoal(int $id, int $userId);
    public function editGoal(int $id, string $name, string $description, int $userId);
    public function deleteGoal(int $id, int $userId);
    public function setDeadline(string $deadline, int $userId);
}
