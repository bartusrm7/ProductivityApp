<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\JWTService;
use App\Services\TasksDataService;

class TasksDataController extends BaseController
{
    private TasksDataService $service;
    private JWTService $jwtservice;

    public function __construct(TasksDataService $service, JWTService $jwtservice)
    {
        $this->service = $service;
        $this->jwtservice = $jwtservice;
    }

    public function setDeadline()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $data = $this->jsonInput();
        $deadline = $data['deadline'];
        $taskId = $data['taskId'];

        $result = $this->service->setDeadline($deadline, $taskId);
        $status = $result['success'] ? 200 : 422;
        $this->jsonResponse($result, $status);
    }
}
