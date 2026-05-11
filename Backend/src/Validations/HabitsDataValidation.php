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
    public function emptyCheckCurrentDay(string $checkCurrentDay)
    {
        if (empty($checkCurrentDay)) {
            return 'Check current day is empty';
        }
    }
}
