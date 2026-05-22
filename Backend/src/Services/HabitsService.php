<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ActivityLogRepository;
use App\Repositories\HabitsRepository;
use App\Services\Interfaces\HabitsServiceInterface;
use App\Validations\HabitsValidation;
use DateTime;

class HabitsService extends BaseService implements HabitsServiceInterface
{
    private HabitsRepository $repository;
    private ActivityLogRepository $activeLogs;
    private HabitsValidation $validation;
    public function __construct(HabitsRepository $repository, ActivityLogRepository $activeLogs, HabitsValidation $validation)
    {
        $this->repository = $repository;
        $this->activeLogs = $activeLogs;

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
        $currentCreatedAt = new DateTime('now');
        $result = $this->repository->newHabitQuery($name, $newCreatedAt, $userId);
        $habitId = $result->getId();
        $this->activeLogs->createActivityLogQuery('create', 'habits', $habitId, $currentCreatedAt, $userId);
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
        $currentCreatedAt = new DateTime('now');
        $this->repository->editHabitQuery($id, $name, $description, $userId);
        $this->activeLogs->createActivityLogQuery('edit', 'habits', $id, $currentCreatedAt, $userId);
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
        $currentCreatedAt = new DateTime('now');
        $this->repository->deleteHabitQuery($id, $userId);
        $this->activeLogs->createActivityLogQuery('delete', 'habits', $id, $currentCreatedAt, $userId);
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
        $currentCreatedAt = new DateTime('now');
        $this->repository->habitStatusStartedQuery($id, $userId);
        $this->activeLogs->createActivityLogQuery('started', 'habits', $id, $currentCreatedAt, $userId);
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
