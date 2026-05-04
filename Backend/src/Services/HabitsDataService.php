<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\HabitsDataRepository;
use App\Services\Interfaces\HabitsDataServiceInterface;
use App\Validations\HabitsValidation;
use DateTime;

class HabitsDataService implements HabitsDataServiceInterface
{
    private HabitsDataRepository $repository;
    private HabitsValidation $habitsValidation;

    public function __construct(HabitsDataRepository $repository, HabitsValidation $habitsValidation)
    {
        $this->repository = $repository;
        $this->habitsValidation = $habitsValidation;
    }

    public function setHabitThisDayDone(int $id, string $checkCurrentDay)
    {
        $errors = [];
        if ($error = $this->habitsValidation->emptyHabitId($id)) {
            $errors[] = $error;
        }
        if ($error = $this->habitsValidation->emptyStatus($checkCurrentDay)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $newCheckCurrentDay = new DateTime($checkCurrentDay);
            $this->repository->setHabitThisDayDoneQuery($id, $newCheckCurrentDay);
            return [
                'success' => true
            ];
        }
    }
}
