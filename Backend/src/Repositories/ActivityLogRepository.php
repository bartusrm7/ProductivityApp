<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use DateTime;
use PDO;

class ActivityLogRepository
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function createActivityLogQuery(string $action, string $entity, int $entityId, DateTime $createdAt, int $userId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO active_logs (action, entity, entity_id, created_at, user_id) VALUES (:action, :entity, :entity_id, :created_at, :user_id)');
        $stmt->execute([':action' => $action, ':entity' => $entity, ':entity_id' => $entityId, ':created_at' => $createdAt->format('Y-m-d H:i:s'), ':user_id' => $userId]);
    }
}
