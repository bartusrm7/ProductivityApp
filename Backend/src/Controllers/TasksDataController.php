<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\TasksDataService;

class TasksDataController extends BaseController
{
    private TasksDataService $service;

    public function __construct(TasksDataService $service)
    {
        $this->service = $service;
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
        $status = isset($result['success']) ? 200 : 422;
        
        $this->jsonResponse($result, $status);
    }
}
