<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\GoalsService;
use App\Services\JWTService;

class GoalsController extends BaseController
{
    private GoalsService $service;
    private JWTService $jwtservice;

    public function __construct(GoalsService $service, JWTService $jwtservice)
    {
        $this->service = $service;
        $this->jwtservice = $jwtservice;
    }

    public function createGoal()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $data = $this->jsonInput();
        $name = $data['name'];
        $type = $data['type'];
        $createdAt = $data['created_at'];

        $result = $this->service->createGoal($name, $type, $createdAt, $userId);
        $status = isset($result['success']) ? 201 : 422;

        $this->jsonResponse($result, $status);
    }

    public function getGoals()
    {
        if (!$this->requestMethod('GET')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $result = $this->service->getGoals($userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function editGoal()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $data = $this->jsonInput();
        $id = $data['id'];
        $name = $data['name'];
        $description = $data['description'];

        $result = $this->service->editGoal($id, $name, $description, $userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function sortGoals()
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

        $result = $this->service->sortGoals($params, $userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }
}
