<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\NotesRepository;
use App\Services\Interfaces\NotesServiceInterface;
use App\Validations\NotesValidation;
use DateTime;

class NotesService implements NotesServiceInterface
{
    private NotesRepository $repository;
    private NotesValidation $validation;

    public function __construct(NotesRepository $repository, NotesValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function createNote(string $name, string $tag,  string $createdAt, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyNoteName($name)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyNoteTag($tag)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyCreatedAt($createdAt)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $newCreatedAt = new DateTime($createdAt);
            $this->repository->createNewNoteQuery($name, $tag, $newCreatedAt, $userId);
            return [
                'success' => true
            ];
        }
    }
}
