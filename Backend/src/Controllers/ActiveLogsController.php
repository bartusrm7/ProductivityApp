<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ActiveLogsService;

class ActiveLogsController extends BaseController
{
    private ActiveLogsService $service;

    public function __construct(ActiveLogsService $service)
    {
        $this->service = $service;
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
}
