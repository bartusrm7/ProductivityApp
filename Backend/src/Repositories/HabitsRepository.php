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
            $createdAt,
            ''
        );
    }

    public function getAllHabitsQuery(int $userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM habits WHERE status = 'in_progress' AND user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function editHabitQuery(int $id, string $name, string $description, int $userId)
    {
        $stmt = $this->pdo->prepare('UPDATE habits SET name = :name, description = :description WHERE id = :id AND user_id = :user_id');
        $stmt->execute([':id' => $id, ':name' => $name, ':description' => $description, ':user_id' => $userId]);

        return new HabitsModel(
            $id,
            $name,
            $description,
            new DateTime,
            ''
        );
    }

    public function deleteHabitQuery(int $id, int $userId)
    {
        $stmt = $this->pdo->prepare('DELETE FROM habits WHERE id = :id AND user_id = :user_id');
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        return $stmt->rowCount();
    }

    public function sortHabitsDataQuery(array $params)
    {
        $sql = 'SELECT * FROM habits AS h LEFT JOIN habits_data AS hd ON hd.habit_id = h.id WHERE user_id = :user_id AND h.status = :status';
        $bindings = [':status' => $params['status'], ':user_id' => $params['user_id']];

        $sortData = ['id', 'name', 'description', 'created_at', 'status', 'streak_days', 'check_current_day', 'amount_days_done'];
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

    public function habitStatusStartedQuery(int $id, int $userId)
    {
        $stmt = $this->pdo->prepare("UPDATE habits SET status = 'started' WHERE id = :id AND user_id = :user_id");
        $stmt->execute([':id' => $id, ':user_id' => $userId]);

        $stmt = $this->pdo->prepare('INSERT INTO habits_data (habit_id, streak_days, check_current_day, amount_days_done) VALUES (:habit_id, 0, CURDATE(), 0)');
        $stmt->execute([':habit_id' => $id]);
    }

    public function getHabitsWithStartedStatusQuery(string $status, int $userId)
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM habits AS h
            INNER JOIN habits_data AS hd ON h.id = hd.habit_id
            WHERE h.user_id = :user_id
            AND h.status = :status'
        );
        $stmt->execute([':status' => $status, ':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getCurrentHabitStreaksQuery(int $userId)
    {
        $stmt = $this->pdo->prepare(
            'SELECT h.name, hd.streak_days FROM habits AS h
            INNER JOIN habits_data AS hd ON hd.habit_id = h.id
            WHERE h.user_id = :user_id
            AND hd.check_current_day = CURDATE()
            ORDER BY hd.streak_days DESC
            LIMIT 1'
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch();
    }

    public function getBestOverallHabitStreaksQuery(int $userId)
    {
        $stmt = $this->pdo->prepare(
            'SELECT h.name, hd.streak_days FROM habits AS h
            INNER JOIN habits_data AS hd ON hd.habit_id = h.id
            WHERE h.user_id = :user_id
            ORDER BY hd.streak_days DESC
            LIMIT 1'
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch();
    }
}
