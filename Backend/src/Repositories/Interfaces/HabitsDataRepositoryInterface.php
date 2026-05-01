<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface HabitsDataRepositoryInterface
{
    public function setHabitThisDayDoneQuery(int $id, string $checkCurrentDay, int $userId);
}
