<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use DateTime;

interface HabitsDataRepositoryInterface
{
    public function setHabitThisDayDoneQuery(int $id, DateTime $checkCurrentDay);
    public function countCurrentStreakDaysQuery(int $id, int $streakDays);
    public function countAmountDaysDoneQuery(int $id, int $amountDaysDone);
}
