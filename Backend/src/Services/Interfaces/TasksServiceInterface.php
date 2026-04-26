<?php

declare(strict_types=1);

namespace App\Services\Interfaces;


interface TasksServiceInterface
{
    public function createNewTask(string $name, string $createdAt, string $priority, int $userId);
    public function editTask(int $id,  string $status, string $priority,int $userId);
    public function deleteTask(int $id, int $userId);
}
