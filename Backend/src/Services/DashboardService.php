<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\DashboardRepository;
use App\Validations\DashboardValidation;

class DashboardService extends BaseService
{
    private DashboardRepository $repository;
    private DashboardValidation $validation;

    public function __construct(DashboardRepository $repository, DashboardValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function getAllLogs(int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $result = $this->repository->getAllActiveLogsQuery($userId);
        return $this->successResponseWithData($result);
    }
}
