<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

interface HabitsDataServiceInterface
{
    public function setHabitThisDayDone(int $id, string $checkCurrentDay);

    public function countCurrentStreakDays(int $id, string $checkCurrentDay, int $streakDays);
    public function countAmountDaysDone(int $id, int $amountDaysDone);
}
