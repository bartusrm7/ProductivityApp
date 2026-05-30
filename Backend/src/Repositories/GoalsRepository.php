<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\GoalsModel;
use DateTime;
use PDO;

class GoalsRepository
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
}
