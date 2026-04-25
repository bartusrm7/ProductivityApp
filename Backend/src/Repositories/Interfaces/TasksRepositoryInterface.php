<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use DateTime;

interface TasksRepositoryInterface
{
    public function createNewTaskQuery(string $name, DateTime $createdAt, string $priority, int $userId);
}
