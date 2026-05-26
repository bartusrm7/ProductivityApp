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

    public function createNewTaskQuery(string $name, DateTime $createdAt, string $priority, int $userId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO tasks (name, created_at, priority, user_id) VALUES (:name, :created_at, :priority, :user_id)');
        $stmt->execute([':name' => $name, ':created_at' => $createdAt->format('Y-m-d H:i:s'), ':priority' => $priority, ':user_id' => $userId]);
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
        $stmt = $this->pdo->prepare(
            "SELECT * FROM tasks AS t
            LEFT JOIN tasks_data AS td ON td.task_id = t.id
            WHERE t.user_id = :user_id
            AND t.status = 'in_progress'"
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getDoneTasksQuery(int $userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE status = 'done' AND user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function doneTaskQuery(int $id, string $status, int $userId)
    {
        $stmt = $this->pdo->prepare("UPDATE tasks SET status = :status, created_at = NOW() WHERE id = :id AND user_id = :user_id");
        $stmt->execute([':id' => $id, ':status' => $status, ':user_id' => $userId]);

        return new TasksModel(
            $id,
            '',
            new DateTime,
            '',
            $status
        );
    }

    public function editTaskQuery(int $id, string $name, string $priority, int $userId)
    {
        $stmt = $this->pdo->prepare('UPDATE tasks SET name = :name, priority = :priority WHERE id = :id AND user_id = :user_id');
        $stmt->execute([':id' => $id, ':name' => $name, ':priority' => $priority, ':user_id' => $userId]);

        return new TasksModel(
            $id,
            $name,
            new DateTime,
            $priority,
            ''
        );
    }

    public function deleteTaskQuery(int $id, int $userId)
    {
        $stmt = $this->pdo->prepare('DELETE FROM tasks WHERE id = :id AND user_id = :user_id');
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        return $stmt->rowCount();
    }

    public function sortDataByStatusAndTitleQuery(array $params)
    {
        $sql = 'SELECT * FROM tasks WHERE status = :status AND user_id = :user_id';
        $bindings = [':status' => $params['status'], ':user_id' => $params['user_id']];

        $sortData = ['id', 'name', 'created_at', 'priority'];
        $directionsData = ['ASC', 'DESC'];

        if (!empty($params['sort'])) {
            $sort = in_array($params['sort'], $sortData) ? $params['sort'] : 'name';
            $direction = in_array(strtoupper($params['direction'] ?? 'ASC'), $directionsData) ? strtoupper($params['direction']) : 'ASC';
            $sql .= " ORDER BY $sort $direction";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchAll();
    }

    public function getTodayTasksQuery(string $status, int $userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM tasks WHERE status = :status AND user_id = :user_id AND DATE(created_at) = CURDATE()');
        $stmt->execute([':status' => $status, ':user_id' => $userId]);
        return $stmt->fetchAll();
    }
}
