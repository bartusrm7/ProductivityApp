<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\JWTService;
use App\Services\TasksService;

class TasksController
{
    private TasksService $service;
    private JWTService $jwtservice;
    public function __construct(TasksService $service, JWTService $jwtservice)
    {
        $this->service = $service;
        $this->jwtservice = $jwtservice;
    }

    public function getJWToken()
    {
        $authorization = $_SERVER['HTTP_AUTHORIZATION'];
        $jwt = str_replace('Bearer ', '', $authorization);
        return $jwt;
    }

    public function createNewTask()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jwt = $this->getJWToken();
            $decoded = $this->jwtservice->decodeToken($jwt);
            $userId = $decoded->user_id;

            $data = json_decode(file_get_contents('php://input'), true);
            $name = $data['name'];
            $createdAt = $data['created_at'];
            $priority = $data['priority'];

            $result = $this->service->createNewTask($name, $createdAt, $priority, $userId);
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

    public function doneTask()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jwt = $this->getJWToken();
            $decoded = $this->jwtservice->decodeToken($jwt);
            $userId = $decoded->user_id;

            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'];
            $status = $data['status'];

            $result = $this->service->doneTask($id, $status, $userId);
            if (isset($result['success'])) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
            }
        }
    }

    public function editTask()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jwt = $this->getJWToken();
            $decoded = $this->jwtservice->decodeToken($jwt);
            $userId = $decoded->user_id;

            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'];
            $name = $data['name'];
            $priority = $data['priority'];

            $result = $this->service->editTask($id, $name, $priority, $userId);
            if (isset($result['success'])) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
            }
        }
    }

    public function deleteTask()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jwt = $this->getJWToken();
            $decoded = $this->jwtservice->decodeToken($jwt);
            $userId = $decoded->user_id;

            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'];

            $result = $this->service->deleteTask($id, $userId);
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
