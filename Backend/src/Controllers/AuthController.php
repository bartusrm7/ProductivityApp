<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\JWTService;

class AuthController extends BaseController
{
    private AuthService $service;
    private JWTService $jwtService;
    public function __construct(AuthService $service, JWTService $jwtService)
    {
        $this->service = $service;
        $this->jwtService = $jwtService;
    }

    public function userRegistration()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $data = $this->jsonInput();
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

    public function userLogin()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $data = $this->jsonInput();
        $email = $data['email'];
        $password = $data['password'];

        $result = $this->service->userLogin($email, $password);
        if (isset($result['success'])) {
            $userId = $result['id'];
            $name = $result['name'];
            $token = $this->jwtService->generateToken($name, $userId);
            http_response_code(200);
            echo json_encode(['data' => $result, 'token' => $token]);
        } else {
            http_response_code(422);
            echo json_encode($result);
            return;
        }
    }

    public function getLoggedUserName()
    {
        $userName = $this->jwtService->getUserNameFromJWT();
        echo json_encode($userName);
    }

    public function getAvatar()
    {
        if (!$this->requestMethod('GET')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtService->getUserIdFromJWT();

        $result = $this->service->getAvatar($userId);
        if (isset($result['success'])) {
            http_response_code(200);
            echo json_encode(['avatar' => $result]);
        } else {
            http_response_code(422);
            echo json_encode($result);
            return;
        }
    }
}
