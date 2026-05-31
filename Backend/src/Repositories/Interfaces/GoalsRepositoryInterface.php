<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use DateTime;

interface GoalsRepositoryInterface
{
    public function createNewGoalQuery(string $name, string $type, DateTime $createdAt, int $userId);
    public function doneGoalQuery(int $id, int $userId);
    public function editGoalQuery(int $id, string $name, string $description, int $userId);
    public function deleteGoalQuery(int $id, int $userId);
}
