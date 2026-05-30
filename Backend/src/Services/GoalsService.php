<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\GoalsRepository;
use App\Services\Interfaces\GoalsServiceInterface;

class GoalsService implements GoalsServiceInterface
{
    private GoalsRepository $repository;

    public function __construct(GoalsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createGoal(string $name, string $type, string $createdAt, int $userId) {}
}
