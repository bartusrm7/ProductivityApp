<?php

declare(strict_types=1);

namespace App\Validations;

class DashboardValidation
{
    public function emptyUserId(int $userId)
    {
        if (empty($userId)) {
            return 'UserID is not exists';
        }
    }
}
