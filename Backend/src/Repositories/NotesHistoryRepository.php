<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Repositories\Interfaces\NotesHistoryRepositoryInterface;
use PDO;

class NotesHistoryRepository implements NotesHistoryRepositoryInterface
{
    private PDO $pdo;
    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }
}
