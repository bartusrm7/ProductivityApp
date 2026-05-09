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

    public function createNewNoteQuery(string $name, string $tag,  DateTime $createdAt, int $userId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO notes (name, tag, created_at, user_id) VALUES (:name, :tag, :created_at, :user_id)');
        $stmt->execute([':name' => $name, ':tag' => $tag, ':created_at' => $createdAt->format('Y-m-d 00:00:00'), ':user_id' => $userId]);
        $id = (int) $this->pdo->lastInsertId();

        return new NotesModel(
            $id,
            $name,
            $tag,
            $createdAt
        );
    }
}
