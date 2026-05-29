<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\JWTService;
use App\Services\NotesService;

class NotesController extends BaseController
{
    private NotesService $service;
    private JWTService $jwtservice;
    public function __construct(NotesService $service, JWTService $jwtservice)
    {
        $this->service = $service;
        $this->jwtservice = $jwtservice;
    }

    public function createNote()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $data = $this->jsonInput();
        $name = $data['name'];
        $tag = $data['tag'];
        $createdAt = $data['created_at'];

        $result = $this->service->createNote($name, $tag, $createdAt, $userId);
        $status = isset($result['success']) ? 201 : 422;

        $this->jsonResponse($result, $status);
    }

    public function getNotes()
    {
        if (!$this->requestMethod('GET')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $result = $this->service->getNotes($userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function getSavedToHistoryNotes()
    {
        if (!$this->requestMethod('GET')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $result = $this->service->getSavedToHistoryNotes($userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function setImportantNote()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $data = $this->jsonInput();
        $id = $data['id'];
        $important = $data['important'];

        $result = $this->service->setImportantNote($id, (bool) $important, $userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function saveNoteIntoHistory()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $data = $this->jsonInput();
        $id = $data['id'];
        $savedToHistory = $data['saveToHistory'];

        $result = $this->service->saveNoteIntoHistory($id, (bool) $savedToHistory, $userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function editNote()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $data = $this->jsonInput();
        $id = $data['id'];
        $name = $data['name'];
        $tag = $data['tag'];

        $result = $this->service->editNote($id, $name, $tag, $userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function deleteNote()
    {
        if (!$this->requestMethod('POST')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();

        $data = $this->jsonInput();
        $id = $data['id'];

        $result = $this->service->deleteNote($id, $userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }

    public function sortNotes()
    {
        if (!$this->requestMethod('GET')) {
            $this->jsonResponseMethodNotAllowed();
        }
        $userId = $this->jwtservice->getUserIdFromJWT();
        $params = [
            'saved_to_history' => $_GET['saved_to_history'] ?? null,
            'sort'             => $_GET['sort'] ?? null,
            'direction'        => $_GET['direction'] ?? null,
            'user_id'          => $userId
        ];
        
        $result = $this->service->sortNotes($params, $userId);
        $status = isset($result['success']) ? 200 : 422;

        $this->jsonResponse($result, $status);
    }
}
