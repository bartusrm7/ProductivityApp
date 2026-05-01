<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;

class HabitsDataModel
{
    public function __construct(private int $id, private int $streakDays, private DateTime $checkCurrentDay, private int $amountDaysDone) {}
    public function getId()
    {
        return $this->id;
    }
    public function getStreakDays()
    {
        return $this->streakDays;
    }
    public function getCheckCurrentDay()
    {
        return $this->checkCurrentDay;
    }
    public function getAmountDaysDone()
    {
        return $this->amountDaysDone;
    }
}
