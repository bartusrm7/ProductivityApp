<?php

declare(strict_types=1);

namespace App\Controllers;

class BaseController
{
    protected function jsonInput()
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

    protected function jsonResponse(array $result, int $status = 200)
    {
        if (isset($result['success'])) {
            http_response_code($status);
            echo json_encode($result);
        } else {
            http_response_code(422);
            echo json_encode($result);
        }
    }

    protected function jsonResponseMethodNotAllowed()
    {
        http_response_code(405);
        echo json_encode(['errors' => 'Method not allowed']);
        exit;
    }

    protected function requestMethod(string $method)
    {
        return $_SERVER['REQUEST_METHOD'] === $method;
    }
}
