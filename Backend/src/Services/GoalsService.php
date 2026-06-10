<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ActivityLogRepository;
use App\Repositories\GoalsRepository;
use App\Services\Interfaces\GoalsServiceInterface;
use App\Validations\GoalsValidation;
use DateTime;

class GoalsService extends BaseService implements GoalsServiceInterface
{
    private GoalsRepository $repository;
    private ActivityLogRepository $activeLogs;
    private GoalsValidation $validation;

    public function __construct(GoalsRepository $repository, ActivityLogRepository $activeLogs, GoalsValidation $validation)
    {
        $this->repository = $repository;
        $this->activeLogs = $activeLogs;
        $this->validation = $validation;
    }

    public function createGoal(string $name, string $type, string $createdAt, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyGoalName($name)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyType($type)) {
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
        $newCreatedAt = new DateTime($createdAt);
        $currentCreatedAt = new DateTime('now');
        $result = $this->repository->createNewGoalQuery($name, $type, $newCreatedAt, $userId);
        $goalId = $result->getId();
        $this->activeLogs->createActivityLogQuery($name, 'create', 'goal', $goalId, $currentCreatedAt, $userId);
        return $this->successResponse();
    }

    public function getGoalsInProgress(int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $result = $this->repository->getGoalsInProgressQuery($userId);
        return $this->successResponseWithData($result);
    }

    public function getGoalsDone(int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $result = $this->repository->getGoalsDoneQuery($userId);
        return $this->successResponseWithData($result);
    }

    public function doneGoal(int $id, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyGoalId($id)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $currentCreatedAt = new DateTime('now');
        $this->repository->doneGoalQuery($id, $userId);
        $this->activeLogs->createActivityLogQuery('', 'done', 'goal', $id, $currentCreatedAt, $userId);
        return $this->successResponse();
    }

    public function editGoal(int $id, string $name, string $description, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyGoalId($id)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyGoalName($name)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $currentCreatedAt = new DateTime('now');
        $this->repository->editGoalQuery($id, $name, $description, $userId);
        $this->activeLogs->createActivityLogQuery($name, 'edit', 'goal', $id, $currentCreatedAt, $userId);
        return $this->successResponse();
    }

    public function deleteGoal(int $id, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyGoalId($id)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $currentCreatedAt = new DateTime('now');
        $this->repository->deleteGoalQuery($id, $userId);
        $this->activeLogs->createActivityLogQuery('', 'delete', 'goal', $id, $currentCreatedAt, $userId);
        return $this->successResponse();
    }

    public function sortGoals(array $params, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyStatus($params['status'])) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $result = $this->repository->sortGoalsDataQuery($params);
        return $this->successResponseWithData($result);
    }

    public function setDeadline(string $deadline, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyDeadline($deadline)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $currentCreatedAt = new DateTime('now');
        $newDeadline = new DateTime($deadline);
        $this->repository->setDeadlineDayQuery($newDeadline, $userId);
        $this->activeLogs->createActivityLogQuery('', 'set', 'goal', 0, $currentCreatedAt, $userId);
        return $this->successResponse();
    }
}
