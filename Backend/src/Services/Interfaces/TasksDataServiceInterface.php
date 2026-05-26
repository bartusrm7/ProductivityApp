<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

interface TasksDataServiceInterface
{
    public function setDeadline(string $deadline, int $taskId);
}
