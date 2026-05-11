<?php

declare(strict_types=1);

namespace App\Validations;

class NotesValidation
{
    public function emptyNoteId(int $id)
    {
        if (empty($id)) {
            return 'Note ID is not exists';
        }
    }
    public function emptyNoteName(string $name)
    {
        if (empty($name)) {
            return 'Name input field is empty';
        }
    }
    public function emptyNoteTag(string $tag)
    {
        if (empty($tag)) {
            return 'Tag input field is empty';
        }
    }
    public function emptyCreatedAt(string $createdAt)
    {
        if (empty($createdAt)) {
            return 'Created at time is not exists';
        }
    }
    public function emptyImportantNote(bool $important)
    {
        if (!isset($important)) {
            return 'Important mark note is not exists';
        }
    }
    public function emptyUserId(int $userId)
    {
        if (empty($userId)) {
            return 'UserID is not exists';
        }
    }
}
