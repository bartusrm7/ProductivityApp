<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ActivityLogRepository;
use App\Validations\ActiveLogsValidation;

class ActiveLogsService extends BaseService
{
    private ActivityLogRepository $repository;
    private ActiveLogsValidation $validation;

    public function __construct(ActivityLogRepository $repository, ActiveLogsValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function setLogsAsReaded(int $id)
    {
        $this->repository->setLogsAsReadedQuery($id);
        return $this->successResponse();
    }

    public function getNoReadedLogs(int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $result = $this->repository->getNoReadedLogsQuery($userId);
        return $this->successResponseWithData($result);
    }
}
