<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\JWTService;
use App\Services\TasksService;

class TasksController extends BaseController
{
    private TasksService $service;
    private JWTService $jwtservice;
    public function __construct(TasksService $service, JWTService $jwtservice)
    {
        $this->service = $service;
        $this->jwtservice = $jwtservice;
    }

    public function createNewTask()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $data = $this->jsonInput();
        $name = $data['name'];
        $createdAt = $data['created_at'];
        $priority = $data['priority'];

        $result = $this->service->createNewTask($name, $createdAt, $priority, $userId);
        $status = isset($result['success']) ? 201 : 422;

        $this->jsonResponse($result, $status);
    }

    public function getToDoTasks()
    {
        if (!$this->requestMethod('GET')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $result = $this->service->getToDoTasks($userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function getInProgressTasks()
    {
        if (!$this->requestMethod('GET')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $result = $this->service->getInProgressTasks($userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function getDoneTasks()
    {
        if (!$this->requestMethod('GET')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $result = $this->service->getDoneTasks($userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function doneTask()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $data = $this->jsonInput();
        $id = $data['id'];
        $statusTask = $data['status'];

        $result = $this->service->doneTask($id, $statusTask, $userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function editTask()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $data = $this->jsonInput();
        $id = $data['id'];
        $name = $data['name'];
        $priority = $data['priority'];

        $result = $this->service->editTask($id, $name, $priority, $userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function deleteTask()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $data = $this->jsonInput();
        $id = $data['id'];

        $result = $this->service->deleteTask($id, $userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function sortTasks()
    {
        if (!$this->requestMethod('GET')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();
        $params = [
            'status'        => $_GET['status'] ?? null,
            'sort'          => $_GET['sort'] ?? null,
            'direction'     => $_GET['direction'] ?? null,
            'user_id'       => $userId
        ];

        $result = $this->service->sortTasks($params, $userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function getTodayTasks()
    {
        if (!$this->requestMethod('GET')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();
        $statusTask = $_GET['status'];

        $result = $this->service->getTodayTasks($statusTask, $userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function taskFailed()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $result = $this->service->taskFailed($userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }
}
