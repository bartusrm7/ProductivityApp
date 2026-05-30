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
}
