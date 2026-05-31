<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\GoalsModel;
use App\Repositories\Interfaces\GoalsRepositoryInterface;
use DateTime;
use PDO;

class GoalsRepository implements GoalsRepositoryInterface
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function createNewGoalQuery(string $name, string $type, DateTime $createdAt, int $userId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO goals (name, type, created_at, user_id) VALUES (:name, :type, :created_at, :user_id)');
        $stmt->execute([':name' => $name, ':type' => $type, ':created_at' => $createdAt->format('Y-m-d H:i:s'), ':user_id' => $userId]);
        $id = (int) $this->pdo->lastInsertId();

        return new GoalsModel(
            $id,
            $name,
            '',
            '',
            $type,
            0,
            $createdAt,
            new DateTime
        );
    }

    public function getGoalsQuery(int $userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM goals WHERE user_id = :user_id');
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function editGoalQuery(int $id, string $name, string $description, int $userId)
    {
        $stmt = $this->pdo->prepare('UPDATE goals SET name = :name, description = :description WHERE id = :id AND user_id = :user_id');
        $stmt->execute([':id' => $id, ':name' => $name, ':description' => $description, ':user_id' => $userId]);

        return new GoalsModel(
            $id,
            $name,
            $description,
            '',
            '',
            0,
            new DateTime,
            new DateTime
        );
    }

    public function deleteGoalQuery(int $id, int $userId)
    {
        $stmt = $this->pdo->prepare('DELETE FROM goals WHERE id = :id AND user_id = :user_id');
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        return $stmt->rowCount();
    }

    public function sortGoalsDataQuery(array $params)
    {
        $sql = 'SELECT * FROM goals WHERE user_id = :user_id';
        $bindings = [':user_id' => $params['user_id']];

        $sortData = ['id', 'name', 'description', 'status', 'created_at', 'deadline'];
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
}
