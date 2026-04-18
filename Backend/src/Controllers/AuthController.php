<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;

class AuthController
{
    private AuthService $service;
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function userRegistration()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $name = $data['name'];
            $email = $data['email'];
            $password = $data['password'];

            $result = $this->service->userRegistration($name, $email, $password);
            if (isset($result['success'])) {
                http_response_code(201);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
                return;
            }
        }
    }

    public function userLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $email = $data['email'];
            $password = $data['password'];

            $result = $this->service->userLogin($email, $password);
            if (isset($result['success'])) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
                return;
            }
        }
    }
}
