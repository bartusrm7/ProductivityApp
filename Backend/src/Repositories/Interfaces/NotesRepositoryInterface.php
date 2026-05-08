<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface NotesRepositoryInterface
{
    public function createNewNoteQuery(string $name, int $userId);
}
