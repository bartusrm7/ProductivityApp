<?php

declare(strict_types=1);

namespace App\Validations;

class HabitsDataValidation
{
    public function emptyHabitDataId(int $id)
    {
        if (empty($id)) {
            return 'Habit data ID does not exist';
        }
    }

    public function emptyStreakDays(int $streakDays)
    {
        if (empty($streakDays)) {
            return 'Streak days value is empty';
        }
    }

    public function emptyCheckCurrentDay(string $checkCurrentDay)
    {
        if (empty($checkCurrentDay)) {
            return 'Check current day is empty';
        }
    }

    public function emptyAmountDaysDone(int $amountDaysDone)
    {
        if (empty($amountDaysDone)) {
            return 'Amount days done is empty';
        }
    }
}
