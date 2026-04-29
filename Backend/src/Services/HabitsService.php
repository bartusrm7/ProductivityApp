<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\HabitsRepository;
use App\Services\Interfaces\HabitsServiceInterface;
use App\Validations\HabitsValidation;
use DateTime;

class HabitsService implements HabitsServiceInterface
{
    private HabitsRepository $repository;
    private HabitsValidation $validation;
    public function __construct(HabitsRepository $repository, HabitsValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function newHabit(string $name, string $createdAt, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyHabitName($name)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyCreatedAt($createdAt)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }

        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $newCreatedAt = new DateTime($createdAt);
            $this->repository->newHabitQuery($name, $newCreatedAt, $userId);
            return [
                'success' => true
            ];
        }
    }

    public function getHabits(int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $result = $this->repository->getHabitsQuery($userId);
            return [
                'success' => true,
                'data'    => $result
            ];
        }
    }
}
