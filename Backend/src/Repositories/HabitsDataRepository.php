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

    public function isHabitCurrentDateExistsTodayQuery(int $id, DateTime $checkCurrentDay)
    {
        $stmt = $this->pdo->prepare('SELECT check_current_day FROM habits_data WHERE id = :id AND check_current_day = :check_current_day');
        $stmt->execute([':id' => $id, ':check_current_day' => $checkCurrentDay->format('Y-m-d 00:00:00')]);
        return $stmt->fetch();
    }

    public function setHabitThisDayDoneQuery(int $id, DateTime $checkCurrentDay)
    {
        $stmt = $this->pdo->prepare('UPDATE habits_data SET check_current_day = :check_current_day WHERE id = :id');
        $stmt->execute([':id' => $id, ':check_current_day' => $checkCurrentDay->format('Y-m-d 00:00:00')]);

        return new HabitsDataModel(
            $id,
            0,
            $checkCurrentDay,
            0
        );
    }

    public function countCurrentStreakDaysQuery(int $id, int $streakDays)
    {
        $stmt = $this->pdo->prepare('UPDATE habits_data SET streak_days = :streak_days WHERE id = :id');
        $stmt->execute([':id' => $id, ':streak_days' => $streakDays]);

        return new HabitsDataModel(
            $id,
            $streakDays,
            new DateTime,
            0
        );
    }

    public function countAmountDaysDoneQuery(int $id, int $amountDaysDone)
    {
        $stmt = $this->pdo->prepare('UPDATE habits_data SET amount_days_done = :amount_days_done WHERE id = :id');
        $stmt->execute([':id' => $id, ':amount_days_done' => $amountDaysDone]);

        return new HabitsDataModel(
            $id,
            0,
            new DateTime,
            $amountDaysDone
        );
    }

    public function getCurrectStreakDaysQuery(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT streak_days FROM habits_data WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn();
    }

    public function getLastCheckDayQuery(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT check_current_day FROM habits_data WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn();
    }
}
