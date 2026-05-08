<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\NotesModel;
use App\Repositories\Interfaces\NotesRepositoryInterface;
use DateTime;
use PDO;

class NotesRepository implements NotesRepositoryInterface
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function createNewNoteQuery(string $name, int $userId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO notes (name, user_id) VALUES (:name, :user_id)');
        $stmt->execute([':name' => $name, ':user_id' => $userId]);
        $id = (int) $this->pdo->lastInsertId();

        return new NotesModel(
            $id,
            $name,
            '',
            new DateTime
        );
    }
}
