<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Repositories\Interfaces\TasksDataRepositoryInterface;
use DateTime;
use PDO;

class TasksDataRepository implements TasksDataRepositoryInterface
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function setDeadlineDateQuery(DateTime $deadline, int $taskId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO tasks_data (deadline, task_id) VALUES (:deadline, :task_id) ON DUPLICATE KEY UPDATE deadline = VALUES(deadline)');
        $stmt->execute([':deadline' => $deadline->format('Y-m-d 23:59:59'), ':task_id' => $taskId]);
        return $stmt->rowCount();
    }

    public function getDeadlineDayQuery(DateTime $deadline, int $taskId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM tasks_data WHERE task_id = :task_id AND deadline = :deadline');
        $stmt->execute([':deadline' => $deadline->format('Y-m-d 23:59:59'), ':task_id' => $taskId]);
        return $stmt->fetch();
    }
}
