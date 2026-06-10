<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use PDO;

class DashboardRepository
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function getAllActiveLogsQuery(int $userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM active_logs WHERE user_id = :user_id ORDER BY id DESC LIMIT 5');
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }
}
