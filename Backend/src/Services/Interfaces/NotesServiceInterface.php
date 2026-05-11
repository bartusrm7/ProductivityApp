<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

interface NotesServiceInterface
{
    public function createNote(string $name, string $tag, string $createdAt,  int $userId);
    public function setImportantNote(int $id, bool $important, int $userId);
    public function deleteNote(int $id, int $userId);
}
