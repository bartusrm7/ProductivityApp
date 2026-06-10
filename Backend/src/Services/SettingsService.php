<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ActivityLogRepository;
use App\Repositories\SettingsRepository;
use App\Services\BaseService;
use App\Services\Interfaces\SettingsServiceInterface;
use App\Validations\SettingsValidation;
use DateTime;

class SettingsService extends BaseService implements SettingsServiceInterface
{
    private SettingsRepository $repository;
    private ActivityLogRepository $activityLog;
    private SettingsValidation $validation;

    public function __construct(SettingsRepository $repository, ActivityLogRepository $activityLog, SettingsValidation $validation)
    {
        $this->repository = $repository;
        $this->activityLog = $activityLog;
        $this->validation = $validation;
    }

    public function updateAvatar(int $id, array $avatar)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($id)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyAvatarField($avatar)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $currentCreatedAt = new DateTime('now');
        $result = $this->repository->updateAvatarQuery($id, $avatar);
        $userName = $result->getName();
        $this->activityLog->createActivityLogQuery($userName, 'set', 'setting', 0, $currentCreatedAt, $id);
        return $this->successResponse();
    }

    public function displayUserAvatar(int $id)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($id)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $result = $this->repository->displayUserAvatarQuery($id);
        return $this->successResponseWithData($result);
    }

    public function updateUserName(string $name, int $id)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($id)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyNameField($name)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $currentCreatedAt = new DateTime('now');
        $this->repository->updateUserNameQuery($name, $id);
        $this->activityLog->createActivityLogQuery($name, 'set', 'setting', 0, $currentCreatedAt, $id);
        return $this->successResponse();
    }

    public function setReminders(int $id, bool $reminders)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($id)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $currentCreatedAt = new DateTime('now');
        $newReminders = $reminders ? 1 : 0;
        $this->repository->setReminderNotificationsQuery($id, $newReminders);
        $this->activityLog->createActivityLogQuery('', 'set', 'setting', 0, $currentCreatedAt, $id);
        return $this->successResponse();
    }

    public function getRemindersState(int $id)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($id)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $result = $this->repository->getRemindersStateQuery($id);
        return $this->successResponseWithData($result);
    }
}
