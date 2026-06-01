<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\SettingsService;
use App\Services\JWTService;

class SettingsController extends BaseController
{
    private SettingsService $service;
    private JWTService $jwtservice;

    public function __construct(SettingsService $service, JWTService $jwtservice)
    {
        $this->service = $service;
        $this->jwtservice = $jwtservice;
    }

    public function updateAvatar()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $data = $this->jsonInput();
        $avatar = $data['avatar'];

        $result = $this->service->updateAvatar($userId, $avatar);
        $status = $result['success'] ? 200 : 422;

        return $this->jsonResponse($result, $status);
    }
}
