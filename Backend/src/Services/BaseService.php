<?php

declare(strict_types=1);

namespace App\Services;

abstract class BaseService
{
    protected function successResponse()
    {
        return [
            'success' => true,
        ];
    }

    protected function successResponseWithData(array $result = [])
    {
        return [
            'success' => true,
            'data'    => $result
        ];
    }
}
