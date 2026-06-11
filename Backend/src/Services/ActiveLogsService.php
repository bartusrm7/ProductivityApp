<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ActivityLogRepository;

class ActiveLogsService extends BaseService
{
    private ActivityLogRepository $repository;

    public function __construct(ActivityLogRepository $repository)
    {
        $this->repository = $repository;
    }

    public function setLogsAsReaded(int $id)
    {
        $this->repository->setLogsAsReadedQuery($id);
        return $this->successResponse();
    }
}
