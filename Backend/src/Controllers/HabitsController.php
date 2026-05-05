<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\HabitsService;
use App\Services\JWTService;

class HabitsController
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $this->jwtservice->getUserIdFromJWT();

            $data = json_decode(file_get_contents('php://input'), true);
            $name = $data['name'];
            $createdAt = $data['created_at'];

            $result = $this->service->newHabit($name, $createdAt, $userId);
            if (isset($result['success'])) {
                http_response_code(201);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
            }
        }
    }

    public function getHabits()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $userId = $this->jwtservice->getUserIdFromJWT();

            $result = $this->service->getHabits($userId);
            if (isset($result['success'])) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
            }
        }
    }

    public function editHabit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $this->jwtservice->getUserIdFromJWT();

            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['habit_id'];
            $name = $data['name'];
            $description = $data['description'];

            $result = $this->service->editHabit($id, $name, $description, $userId);
            if (isset($result['success'])) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
            }
        }
    }

    public function deleteHabit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $this->jwtservice->getUserIdFromJWT();

            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'];

            $result = $this->service->deleteHabit($id, $userId);
            if (isset($result['success'])) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
            }
        }
    }

    public function habitStatusStarted()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $this->jwtservice->getUserIdFromJWT();

            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'];

            $result = $this->service->habitStatusStarted($id, $userId);
            if (isset($result['success'])) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
            }
        }
    }

    public function getStartedHabits()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $userId = $this->jwtservice->getUserIdFromJWT();
            $status = $_GET['status'];

            $result = $this->service->getStartedHabits($status, $userId);
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
