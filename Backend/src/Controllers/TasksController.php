<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\TasksService;
use DateTime;

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
            $createdAt = $data['createdAt'];
            $priority = $data['priority'];

            $result = $this->service->createNewTask($name, $createdAt, $priority);
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
            $userId = (int) $_GET['userId'];

            $result = $this->service->getToDoTasks($userId);
            if (isset($result['success'])) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
            }
        }
    }

    public function getInProgressTasks()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $userId = (int) $_GET['userId'];

            $result = $this->service->getInProgressTasks($userId);
            if (isset($result['success'])) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
            }
        }
    }

    public function getDoneTasks()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $userId = (int) $_GET['userId'];

            $result = $this->service->getDoneTasks($userId);
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
