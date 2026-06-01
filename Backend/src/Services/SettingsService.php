<?php

declare(strict_types=1);

namespace App\Interfaces;

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

    public function updateAvatar(int $id, string $avatar)
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
}
