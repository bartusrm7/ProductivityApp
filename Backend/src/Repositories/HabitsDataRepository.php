<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\HabitsDataModel;
use App\Repositories\Interfaces\HabitsDataRepositoryInterface;
use DateTime;
use PDO;

class HabitsDataRepository implements HabitsDataRepositoryInterface
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function setHabitThisDayDoneQuery(int $id, DateTime $checkCurrentDay, int $userId)
    {
        $stmt = $this->pdo->prepare('UPDATE habits SET check_current_day = :check_current_day WHERE habit_id = :habit_id AND user_id = :user_id');
        $stmt->execute([':habit_id' => $id, ':check_current_day' => $checkCurrentDay->format('Y:m:d H:i:s'),, ':user_id' => $userId]);

        return new HabitsDataModel(
            $id,
            0,
            $checkCurrentDay,
            0
        );
    }
}
