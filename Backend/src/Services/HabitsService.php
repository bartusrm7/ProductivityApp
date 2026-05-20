<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\HabitsRepository;
use App\Services\Interfaces\HabitsServiceInterface;
use App\Validations\HabitsValidation;
use DateTime;

class HabitsService extends BaseService implements HabitsServiceInterface
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
        }

        $newCreatedAt = new DateTime($createdAt)->modify('+2 hours');
        $this->repository->newHabitQuery($name, $newCreatedAt, $userId);
        return $this->successResponse();
    }

    public function getHabits(int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $result = $this->repository->getAllHabitsQuery($userId);
        return $this->successResponseWithData($result);
    }

    public function editHabit(int $id, string $name, string $description, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyHabitId($id)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyHabitName($name)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyHabitDescription($description)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $this->repository->editHabitQuery($id, $name, $description, $userId);
        return $this->successResponse();
    }

    public function deleteHabit(int $id, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyHabitId($id)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $this->repository->deleteHabitQuery($id, $userId);
        return $this->successResponse();
    }

    public function habitStatusStarted(int $id, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyHabitId($id)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $this->repository->habitStatusStartedQuery($id, $userId);
        return $this->successResponse();
    }
    public function getStartedHabits(string $status, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyStatus($status)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $result = $this->repository->getHabitsWithStartedStatusQuery($status, $userId);
        return $this->successResponseWithData($result);
    }
}
