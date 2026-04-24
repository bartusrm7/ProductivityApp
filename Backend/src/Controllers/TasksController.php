<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\TasksService;

class TasksController
{
    private TasksService $service;
    public function __construct(TasksService $service,)
    {
        $this->service = $service;
    }

    public function createNewTask()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $name = $data['name'];

            $result = $this->service->createNewTask($name);
            if (isset($result['success'])) {
                http_response_code(201);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
            }
        }
    }

    public function getToDoTasks()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $status = $_GET['status'];
            $userId = $_GET['userId'];

            $result = $this->service->getToDoTasks($status, $userId);
            if (isset($result['success'])) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
            }
        }
    }
}
