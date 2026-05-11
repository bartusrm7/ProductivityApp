<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\JWTService;
use App\Services\NotesService;

class NotesController
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $this->jwtservice->getUserIdFromJWT();

            $data = json_decode(file_get_contents('php://input'), true);
            $name = $data['name'];
            $tag = $data['tag'];
            $createdAt = $data['created_at'];

            $result = $this->service->createNote($name, $tag, $createdAt, $userId);
            if (isset($result['success'])) {
                http_response_code(201);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
            }
        }
    }

    public function getNotes()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $userId = $this->jwtservice->getUserIdFromJWT();

            $result = $this->service->getNotes($userId);
            if (isset($result['success'])) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
            }
        }
    }

    public function setImportantNote()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $this->jwtservice->getUserIdFromJWT();

            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'];
            $important = $data['important'];

            $result = $this->service->setImportantNote($id, (bool) $important, $userId);
            if (isset($result['success'])) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
            }
        }
    }

    public function editNote()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $this->jwtservice->getUserIdFromJWT();

            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'];
            $name = $data['name'];
            $tag = $data['tag'];

            $result = $this->service->editNote($id, $name, $tag, $userId);
            if (isset($result['success'])) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
            }
        }
    }

    public function deleteNote()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $this->jwtservice->getUserIdFromJWT();

            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'];

            $result = $this->service->deleteNote($id, $userId);
            if (isset($result['success'])) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(422);
                echo json_encode($result);
            }
        }
    }
}
