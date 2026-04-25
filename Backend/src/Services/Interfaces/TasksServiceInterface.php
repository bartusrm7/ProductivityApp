<?php

declare(strict_types=1);

namespace App\Services\Interfaces;


interface TasksServiceInterface
{
    public function createNewTask(string $name, string $createdAt, string $priority, int $userId);
}
