<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ActiveLogsService;
use App\Services\JWTService;

class ActiveLogsController extends BaseController
{
    private ActiveLogsService $service;
    private JWTService $jwtservice;

    public function __construct(ActiveLogsService $service, JWTService $jwtservice)
    {
        $this->service = $service;
        $this->jwtservice = $jwtservice;
    }

    public function setNotificationAsReaded()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $data = $this->jsonInput();
        $id = $data['id'];

        $result = $this->service->setLogsAsReaded($id);
        $status = $result['success'] ? 200 : 422;

        return $this->jsonResponse($result, $status);
    }

    public function getNoReadedLogs()
    {
        if (!$this->requestMethod('GET')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $result = $this->service->getNoReadedLogs($userId);
        $status = $result['success'] ? 200 : 422;

        return $this->jsonResponse($result, $status);
    }
}
