<?php

declare(strict_types=1);

namespace App\Validations;

class TasksValidation
{
    public function emptyTaskName(string $name)
    {
        if (empty($name)) {
            return 'Name input field is empty';
        }
    }
}
