<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use DateTime;

interface HabitsDataRepositoryInterface
{
    public function setHabitThisDayDoneQuery(int $id, DateTime $checkCurrentDay);
}
