<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\TasksModel;
use App\Repositories\Interfaces\TasksRepositoryInterface;
use DateTime;
use PDO;

class TasksRepository implements TasksRepositoryInterface
{
    private PDO $pdo;
    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function createNewTaskQuery(string $name, DateTime $createdAt, string $priority)
    {
        $stmt = $this->pdo->prepare('INSERT INTO tasks (name, created_at, priority) VALUES (:name, :created_at, :priority)');
        $stmt->execute(['name' => $name, ':created_at' => $createdAt->format('Y:m:d H:i:s'), ':priority' => $priority]);
        $id = (int) $this->pdo->lastInsertId();;

        return new TasksModel(
            $id,
            $name,
            $createdAt,
            $priority,
            ''
        );
    }

    public function getToDoTasksQuery(int $userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE status = 'todo' AND user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getInProgressTasksQuery(int $userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE status = 'in_progress' AND user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getDoneTasksQuery(int $userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE status = 'done' AND user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }
}
