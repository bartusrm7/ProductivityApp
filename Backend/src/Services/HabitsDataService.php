<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\HabitsDataRepository;
use App\Services\Interfaces\HabitsDataServiceInterface;
use App\Validations\HabitsDataValidation;
use DateTime;

class HabitsDataService implements HabitsDataServiceInterface
{
    private HabitsDataRepository $repository;
    private HabitsDataValidation $validation;

    public function __construct(HabitsDataRepository $repository, HabitsDataValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function setHabitThisDayDone(int $id, string $checkCurrentDay)
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
            $newCheckCurrentDay = new DateTime($checkCurrentDay);
            $isTodayDateExists = $this->repository->isHabitCurrentDateExistsTodayQuery($id, $newCheckCurrentDay);
            if (!$isTodayDateExists) {
                $this->repository->setHabitThisDayDoneQuery($id, $newCheckCurrentDay);
                return [
                    'success' => true
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Habit already checked today'
                ];
            }
        }
    }

    public function countCurrentStreakDays(int $id, int $streakDays)
    {
        $errors = [];
        if ($error = $this->validation->emptyHabitDataId($id)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $getLastCheckDay = $this->repository->getLastCheckDayQuery($id);
            $currectStreakDays = $this->repository->getCurrectStreakDays($id);

            if (!$getLastCheckDay) {
                $this->repository->countCurrentStreakDaysQuery($id, 1);
                return [
                    'success' => true
                ];
            }

            $yesterday = new DateTime('yesterday');
            $lastCheckDay = new DateTime($getLastCheckDay);

            if ($yesterday->format('Y-m-d') === $lastCheckDay->format('Y-m-d')) {
                $newStreakDays = $currectStreakDays + 1;
            } else {
                $newStreakDays = 1;
            }

            $this->repository->countCurrentStreakDaysQuery($id, $newStreakDays);
            return [
                'success' => true
            ];
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
                return [
                    'success' => true
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Habit already checked today'
                ];
            }
        }
    }
}
