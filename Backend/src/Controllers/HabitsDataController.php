<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\HabitsDataService;
use App\Services\JWTService;

class HabitsDataController extends BaseController
{
    private HabitsDataService $service;
    private JWTService $jwtservice;

    public function __construct(HabitsDataService $service, JWTService $jwtservice)
    {
        $this->service = $service;
        $this->jwtservice = $jwtservice;
    }

    public function setHabitThisDayDone()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();
        $data = $this->jsonInput();
        $id = $data['id'];
        $checkCurrentDay = $data['checkCurrentDay'];

        $result = $this->service->setHabitThisDayDone($id, $checkCurrentDay, $userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function countCurrentStreakDays()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $data = $this->jsonInput();
        $id = $data['id'];

        $result = $this->service->countCurrentStreakDays($id);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function countAmountDaysDone()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $data = $this->jsonInput();
        $id = $data['id'];
        $amountDaysDone = $data['amountDaysDone'];

        $result = $this->service->countAmountDaysDone($id, $amountDaysDone);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }
}
