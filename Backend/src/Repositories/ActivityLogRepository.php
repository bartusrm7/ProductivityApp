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

    public function createActivityLogQuery(string $name, string $action, string $entity, int $entityId, DateTime $createdAt, int $userId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO active_logs (name, action, entity, entity_id, created_at, user_id) VALUES (:name, :action, :entity, :entity_id, :created_at, :user_id)');
        $stmt->execute([':name' => $name, ':action' => $action, ':entity' => $entity, ':entity_id' => $entityId, ':created_at' => $createdAt->format('Y-m-d H:i:s'), ':user_id' => $userId]);
    }

    public function setLogsAsReadedQuery(int $logId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO read_logs (is_read, log_id) VALUES (1, :log_id) ON DUPLICATE KEY UPDATE is_read = 1');
        $stmt->execute([':log_id' => $logId]);
        return $stmt->fetchAll();
    }

    public function getNoReadedLogsQuery(int $userId)
    {
        $stmt = $this->pdo->prepare(
            'SELECT al.* FROM active_logs AS al
            LEFT JOIN read_logs AS rl ON rl.log_id = al.id
            WHERE al.user_id = :user_id
            AND rl.log_id IS NULL
            ORDER BY al.created_at DESC'
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }
}
