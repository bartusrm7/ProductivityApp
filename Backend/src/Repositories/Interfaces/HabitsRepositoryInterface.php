<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use DateTime;

interface HabitsRepositoryInterface
{
    public function newHabitQuery(string $name, DateTime $createdAt, int $userId);
    public function editHabitQuery(int $id, string $name, string $description, int $userId);
    public function deleteHabitQuery(int $id, int $userId);
}
