<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

interface HabitsServiceInterface
{
    public function newHabit(string $name, string $createdAt, int $userId);
    public function getHabits(int $userId);
}
