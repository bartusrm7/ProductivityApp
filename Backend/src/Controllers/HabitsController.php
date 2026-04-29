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

    public function getToken()
    {
        $authorization = $_SERVER['HTTP_AUTHORIZATION'];
        $jwt = str_replace('Bearer ', '', $authorization);
        return $jwt;
    }

    public function newHabit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jwt = $this->getToken();
            $decoded = $this->jwtservice->decodeToken($jwt);
            $userId = $decoded->user_id;

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
}
