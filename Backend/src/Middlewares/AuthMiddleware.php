<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Services\JWTService;

class AuthMiddleware
{
    private JWTService $jwtservice;

    public function __construct(JWTService $jwtService)
    {
        $this->jwtservice = $jwtService;
    }

    public function userAccess()
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $jwt = str_replace('Bearer ', '', $authHeader);
        $decode = $this->jwtservice->decodeToken($jwt);
        if ($decode) {
            http_response_code(200);
            header('Location: /dashboard');
            echo json_encode(['success' => true]);
            exit;
        } else {
            http_response_code(401);
            echo json_encode(['success' => false]);
        }
    }
}
