<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\HabitsService;
use App\Services\JWTService;

class HabitsController extends BaseController
{
    private HabitsService $service;
    private JWTService $jwtservice;

    public function __construct(HabitsService $service, JWTService $jwtservice)
    {
        $this->service = $service;
        $this->jwtservice = $jwtservice;
    }

    public function newHabit()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $data = $this->jsonInput();
        $name = $data['name'];
        $createdAt = $data['created_at'];

        $result = $this->service->newHabit($name, $createdAt, $userId);
        $status = isset($result['success']) ? 201 : 422;

        $this->jsonResponse($result, $status);
    }

    public function getHabits()
    {
        if (!$this->requestMethod('GET')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $result = $this->service->getHabits($userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function editHabit()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $data = $this->jsonInput();
        $id = $data['habit_id'] ?? $data['id'] ?? null;
        $name = $data['name'];
        $description = $data['description'];

        $result = $this->service->editHabit($id, $name, $description, $userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function deleteHabit()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $data = $this->jsonInput();
        $id = $data['id'];

        $result = $this->service->deleteHabit($id, $userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function habitStatusStarted()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $data = $this->jsonInput();
        $id = $data['id'];

        $result = $this->service->habitStatusStarted($id, $userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function getStartedHabits()
    {
        if (!$this->requestMethod('GET')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();
        $status = $_GET['status'];

        $result = $this->service->getStartedHabits($status, $userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }
}
