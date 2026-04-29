<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\HabitsModel;
use App\Repositories\Interfaces\HabitsRepositoryInterface;
use DateTime;
use PDO;

class HabitsRepository implements HabitsRepositoryInterface
{
    private PDO $pdo;
    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function newHabitQuery(string $name, DateTime $createdAt, int $userId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO habits (name, created_at, user_id) VALUES (:name, :created_at, :user_id)');
        $stmt->execute([':name' => $name, ':created_at' => $createdAt->format('Y:m:d H:i:s'), ':user_id' => $userId]);
        $id = (int) $this->pdo->lastInsertId();

        return new HabitsModel(
            $id,
            $name,
            '',
            $createdAt
        );
    }
}
