<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Repositories\Interfaces\HabitsDataRepositoryInterface;
use PDO;

class HabitsDataRepository implements HabitsDataRepositoryInterface
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

}
