<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ActivityLogRepository;
use App\Repositories\NotesRepository;
use App\Services\Interfaces\NotesServiceInterface;
use App\Validations\NotesValidation;
use DateTime;

class NotesService extends BaseService implements NotesServiceInterface
{
    private NotesRepository $repository;
    private ActivityLogRepository $activeLogs;
    private NotesValidation $validation;

    public function __construct(NotesRepository $repository, ActivityLogRepository $activeLogs, NotesValidation $validation)
    {
        $this->repository = $repository;
        $this->activeLogs = $activeLogs;
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
        $currentCreatedAt = new DateTime('now');
        $result = $this->repository->createNewNoteQuery($name, $tag, $newCreatedAt, $userId);
        $noteId = $result->getId();
        $this->activeLogs->createActivityLogQuery($name, 'create', 'note', $noteId, $currentCreatedAt, $userId);
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
        $currentCreatedAt = new DateTime('now');
        $this->repository->setImportantNoteQuery($id, $important, $userId);
        $this->activeLogs->createActivityLogQuery('', 'set', 'note', $id, $currentCreatedAt, $userId);
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
        $currentCreatedAt = new DateTime('now');
        $savedToHistory = (bool) $savedToHistory;
        $this->repository->saveNoteIntoHistoryQuery($id, $savedToHistory, $userId);
        $this->activeLogs->createActivityLogQuery('', 'set', 'note', $id, $currentCreatedAt, $userId);
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
        $currentCreatedAt = new DateTime('now');
        $this->repository->editNoteQuery($id, $name, $tag, $userId);
        $this->activeLogs->createActivityLogQuery($name, 'set', 'note', $id, $currentCreatedAt, $userId);
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
        $currentCreatedAt = new DateTime('now');
        $result = $this->repository->deleteNoteQuery($id, $userId);
        $this->activeLogs->createActivityLogQuery('', 'set', 'note', $id, $currentCreatedAt, $userId);
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
