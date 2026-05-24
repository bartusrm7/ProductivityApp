<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ActivityLogRepository;
use App\Repositories\HabitsDataRepository;
use App\Services\Interfaces\HabitsDataServiceInterface;
use App\Validations\HabitsDataValidation;
use DateTime;

class HabitsDataService extends BaseService implements HabitsDataServiceInterface
{
    private HabitsDataRepository $repository;
    private ActivityLogRepository $activeLogs;
    private HabitsDataValidation $validation;

    public function __construct(HabitsDataRepository $repository, ActivityLogRepository $activeLogs, HabitsDataValidation $validation)
    {
        $this->repository = $repository;
        $this->activeLogs = $activeLogs;
        $this->validation = $validation;
    }

    public function setHabitThisDayDone(int $id, string $checkCurrentDay, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyHabitDataId($id)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyCheckCurrentDay($checkCurrentDay)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $newCheckCurrentDay = new DateTime($checkCurrentDay)->modify('+2 hours');
            $isTodayDateExists = $this->repository->isHabitCurrentDateExistsTodayQuery($id, $newCheckCurrentDay);
            if (!$isTodayDateExists) {
                $currentCreatedAt = new DateTime('now');
                $this->repository->setHabitThisDayDoneQuery($id, $newCheckCurrentDay);
                $this->activeLogs->createActivityLogQuery('set', 'habits-data', $id, $currentCreatedAt, $userId);
                return $this->successResponse();
            } else {
                return [
                    'success' => false,
                    'message' => 'Habit already checked today'
                ];
            }
        }
    }

    public function countCurrentStreakDays(int $id)
    {
        $errors = [];
        if ($error = $this->validation->emptyHabitDataId($id)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $getLastCheckDay = $this->repository->getLastCheckDayQuery($id);
            $currectStreakDays = $this->repository->getCurrectStreakDaysQuery($id);

            if (!$getLastCheckDay) {
                $this->repository->countCurrentStreakDaysQuery($id, 1);
                return $this->successResponse();
            }

            $today = new DateTime('today');
            $yesterday = new DateTime('yesterday');
            $lastCheckDay = new DateTime($getLastCheckDay);

            if ($today->format('Y-m-d') === $lastCheckDay->format('Y-m-d')) {
                return [
                    'success' => false,
                    'message' => 'Habit already checked today'
                ];
            }

            if ($yesterday->format('Y-m-d') === $lastCheckDay->format('Y-m-d')) {
                $newStreakDays = $currectStreakDays + 1;
            } else {
                $newStreakDays = 1;
            }

            $this->repository->countCurrentStreakDaysQuery($id, $newStreakDays);
            return $this->successResponse();
        }
    }

    public function countAmountDaysDone(int $id, int $amountDaysDone)
    {
        $errors = [];
        if ($error = $this->validation->emptyHabitDataId($id)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $today = new DateTime();
            $currentDayChecked = $this->repository->isHabitCurrentDateExistsTodayQuery($id, $today);

            if (!$currentDayChecked) {
                $this->repository->countAmountDaysDoneQuery($id, $amountDaysDone + 1);
                return $this->successResponse();
            } else {
                return [
                    'success' => false,
                    'message' => 'Habit already checked today'
                ];
            }
        }
    }
}
