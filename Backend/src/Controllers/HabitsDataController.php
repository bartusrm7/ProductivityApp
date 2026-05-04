<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\HabitsDataService;
use App\Services\JWTService;

class HabitsDataController
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'];
            $checkCurrentDay = $data['checkCurrentDay'];

            $result = $this->service->setHabitThisDayDone($id, $checkCurrentDay);
            if (isset($result['success'])) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
            }
        }
    }
}
