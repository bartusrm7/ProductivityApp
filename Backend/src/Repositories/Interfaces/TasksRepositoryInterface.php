<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use DateTime;

interface TasksRepositoryInterface
{
    public function createNewTaskQuery(string $name, DateTime $createdAt, string $priority, int $userId);
    public function doneTaskQuery(int $id, string $status, int $userId);
    public function editTaskQuery(int $id, string $name, string $priority, int $userId);
    public function deleteTaskQuery(int $id, int $userId);
    public function updateTaskFailedQuery(int $id, string $status, int $userId);
}
