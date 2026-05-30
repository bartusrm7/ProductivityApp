<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use PDO;

class GoalsRepository
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function createNewGoalQuery(int $userId) {
        $stmt = $this->pdo->prepare('INSERT INTO goals () VALUES ()');
        $stmt->execute();
    }
}
