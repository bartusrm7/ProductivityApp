<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\DashboardService;
use App\Services\JWTService;

class DashboardController extends BaseController
{
    private DashboardService $service;
    private JWTService $jwtservice;

    public function __construct(DashboardService $service, JWTService $jwtservice)
    {
        $this->service = $service;
        $this->jwtservice = $jwtservice;
    }

    public function getAllLogs()
    {
        if (!$this->requestMethod('GET')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $result = $this->service->getAllLogs($userId);
        $status = $result['success'] ? 200 : 422;

        $this->jsonResponse($result, $status);
    }
}
