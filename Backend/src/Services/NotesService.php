<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\NotesRepository;
use App\Services\Interfaces\NotesServiceInterface;
use App\Validations\NotesValidation;
use DateTime;

class NotesService extends BaseService implements NotesServiceInterface
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
        }
        $newCreatedAt = new DateTime($createdAt)->modify('+2 hours');
        $this->repository->createNewNoteQuery($name, $tag, $newCreatedAt, $userId);
        return $this->successResponse();
    }

    public function getNotes(int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $result = $this->repository->getAllNotesQuery($userId);
        return [
            'success' => true,
            'data'    => array_map(fn($note) => [...$note, 'important' => (bool) $note['important']], $result),
        ];
    }

    public function getSavedToHistoryNotes(int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $result = $this->repository->getSavedToHistoryNotesQuery($userId);
        return [
            'success' => true,
            'data'    => array_map(fn($note) => [...$note, 'important' => (bool) $note['important']], $result),
        ];
    }

    public function setImportantNote(int $id, bool $important, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyNoteId($id)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyImportantNote($important)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $important = (bool) $important;
        $this->repository->setImportantNoteQuery($id, $important, $userId);
        return [
            'success'   => true,
            'important' => $important
        ];
    }

    public function saveNoteIntoHistory(int $id, bool $savedToHistory, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyNoteId($id)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptySavedToHistory($savedToHistory)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $savedToHistory = (bool) $savedToHistory;
        $this->repository->saveNoteIntoHistoryQuery($id, $savedToHistory, $userId);
        return [
            'success'       => true,
            'saveToHistory' => $savedToHistory
        ];
    }

    public function editNote(int $id, string $name, string $tag, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyNoteId($id)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyNoteName($name)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyNoteTag($tag)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $this->repository->editNoteQuery($id, $name, $tag, $userId);
        return $this->successResponse();
    }

    public function deleteNote(int $id, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyNoteId($id)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $this->repository->deleteNoteQuery($id, $userId);
        return $this->successResponse();
    }

    public function sortNotes(array $params, int $userId)
    {
        $errors = [];
        if ($error = $this->validation->emptyUserId($userId)) {
            $errors[] = $error;
        }
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $result = $this->repository->sortNotesQuery($params);
        return $this->successResponseWithData($result);
    }
}
