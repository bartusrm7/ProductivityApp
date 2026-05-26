<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use DateTime;
use PDO;

class TasksDataRepository
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function setDeadlineDateQuery(DateTime $deadline, int $taskId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO tasks_data (deadline, task_id) VALUES (:deadline, :task_id)');
        $stmt->execute([':deadline' => $deadline->format('Y-m-d 00:00:00'), ':task_id' => $taskId]);
        return $stmt->rowCount();
    }
}
