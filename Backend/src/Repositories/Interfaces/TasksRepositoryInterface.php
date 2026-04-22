<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface TasksRepositoryInterface
{
    public function createNewTaskQuery(string $name);
}
