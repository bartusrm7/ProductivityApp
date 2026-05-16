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
            $createdAt,
            false,
            false
        );
    }

    public function getAllNotesQuery(int $userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM notes WHERE saved_to_history = 0 AND user_id = :user_id ORDER BY important DESC');
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getSavedToHistoryNotesQuery(int $userId)
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM notes as n
            INNER JOIN notes_history AS nh ON nh.note_id = n.id
            WHERE n.user_id = :user_id
            AND n.saved_to_history = 1'
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function setImportantNoteQuery(int $id, bool $important, int $userId)
    {
        $important = $important ? 1 : 0;
        $stmt = $this->pdo->prepare('UPDATE notes SET important = :important WHERE id = :id AND user_id = :user_id');
        $stmt->execute([':id' => $id, ':important' => $important, ':user_id' => $userId]);

        return new NotesModel(
            $id,
            '',
            '',
            new DateTime,
            (bool) $important,
            false
        );
    }

    public function saveNoteIntoHistoryQuery(int $id, bool $savedToHistory, int $userId)
    {
        $savedToHistory = $savedToHistory ? 1 : 0;
        $stmt = $this->pdo->prepare('UPDATE notes SET saved_to_history = :saved_to_history WHERE id = :id AND user_id = :user_id');
        $stmt->execute([':id' => $id, ':saved_to_history' => $savedToHistory, ':user_id' => $userId]);

        $stmt = $this->pdo->prepare('INSERT INTO notes_history (date_saved, note_id) VALUES (:date_saved, :note_id)');
        $stmt->execute([':date_saved' => new DateTime()->format('Y-m-d H:i:s'), ':note_id' => $id]);

        return new NotesModel(
            $id,
            '',
            '',
            new DateTime,
            false,
            (bool) $savedToHistory
        );
    }

    public function editNoteQuery(int $id, string $name, string $tag, int $userId)
    {
        $stmt = $this->pdo->prepare('UPDATE notes SET name = :name, tag = :tag WHERE id = :id AND user_id = :user_id');
        $stmt->execute([':id' => $id, ':name' => $name, ':tag' => $tag, ':user_id' => $userId]);

        return new NotesModel(
            $id,
            $name,
            $tag,
            new DateTime,
            false,
            false
        );
    }

    public function deleteNoteQuery(int $id, int $userId)
    {
        $stmt = $this->pdo->prepare('DELETE FROM notes WHERE id = :id AND user_id = :user_id');
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        return $stmt->rowCount();
    }

    public function sortNotesQuery(array $params)
    {
        $sql = 'SELECT * FROM notes WHERE user_id = :user_id';
        $bindings = [':user_id' => $params['user_id']];

        $sortData = ['id', 'name', 'tag', 'created_at'];
        $directionsData = ['ASC', 'DESC'];

        if (!empty($params['sort'])) {
            $sort = in_array($params['sort'], $sortData) ? $params['sort'] : 'name';
            $direction = in_array(strtoupper($params['direction'] ?? 'ASC'), $directionsData) ? strtoupper($params['direction']) : 'ASC';
            $sql .= " ORDER BY {$sort} {$direction}";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchAll();
    }
}
