<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use DateTime;

interface NotesRepositoryInterface
{
    public function createNewNoteQuery(string $name, string $tag, DateTime $createdAt, int $userId);
    public function setImportantNoteQuery(int $id, bool $important, int $userId);
    public function editNoteQuery(int $id, string $note, string $tag, int $userId);
    public function deleteNoteQuery(int $id, int $userId);
}
