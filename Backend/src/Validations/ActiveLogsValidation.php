<?php

declare(strict_types=1);

namespace App\Validations;

class ActiveLogsValidation
{
    public function emptyUserId(int $id)
    {
        if (empty($id)) {
            return 'User ID does not exists';
        }
    }
}
