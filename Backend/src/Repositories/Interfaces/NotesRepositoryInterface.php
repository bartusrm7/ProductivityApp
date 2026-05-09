<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use DateTime;

interface NotesRepositoryInterface
{
    public function createNewNoteQuery(string $name, string $tag, DateTime $createdAt, int $userId);
}
