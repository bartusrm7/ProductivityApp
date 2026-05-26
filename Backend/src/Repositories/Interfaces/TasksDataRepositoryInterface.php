<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use DateTime;

interface TasksDataRepositoryInterface
{
    public function setDeadlineDateQuery(DateTime $deadline, int $taskId);
}
