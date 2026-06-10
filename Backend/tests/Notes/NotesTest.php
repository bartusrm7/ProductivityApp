<?php

declare(strict_types=1);

namespace App\Notes;

use App\Models\NotesModel;
use App\Repositories\ActivityLogRepository;
use App\Repositories\NotesRepository;
use App\Services\NotesService;
use App\Validations\NotesValidation;
use DateTime;
use Override;
use PHPUnit\Framework\TestCase;

class NotesTest extends TestCase
{
    private NotesService $service;
    private NotesRepository $repository;
    private ActivityLogRepository $activeLog;
    private NotesValidation $validation;

    #[Override]
    public function setUp(): void
    {
        $this->repository = $this->createStub(NotesRepository::class);
        $this->activeLog = $this->createStub(ActivityLogRepository::class);
        $this->validation = $this->createStub(NotesValidation::class);
        $this->service = new NotesService($this->repository, $this->activeLog, $this->validation);
    }

    public function testEmptyNoteName()
    {
        $this->validation->method('emptyNoteName')->willReturn('Name input field is empty');
        $this->repository->method('createNewNoteQuery')->willReturn(null);

        $result = $this->service->createNote('', 'sport', '2022-10-09 18:39:16', 1);
        $this->assertEquals(['errors' => ['Name input field is empty']], $result);
    }

    public function testEmptyNoteTag()
    {
        $this->validation->method('emptyNoteTag')->willReturn('Tag input field is empty');
        $this->repository->method('createNewNoteQuery')->willReturn(null);

        $result = $this->service->createNote('note', '', '2022-10-09 18:39:16', 1);
        $this->assertEquals(['errors' => ['Tag input field is empty']], $result);
    }

    public function testEmptyCreatedAt()
    {
        $this->validation->method('emptyCreatedAt')->willReturn('Created at time is not exists');
        $this->repository->method('createNewNoteQuery')->willReturn(null);

        $result = $this->service->createNote('note', 'sport', '', 1);
        $this->assertEquals(['errors' => ['Created at time is not exists']], $result);
    }

    public function testEmptyUserId()
    {
        $this->validation->method('emptyUserId')->willReturn('UserID is not exists');
        $this->repository->method('createNewNoteQuery')->willReturn(null);

        $result = $this->service->createNote('note', 'sport', '2022-10-09 18:39:16', 0);
        $this->assertEquals(['errors' => ['UserID is not exists']], $result);
    }

    public function testCreateNewNote()
    {
        $repo = $this->createMock(NotesRepository::class);

        $this->validation->method('emptyNoteName')->willReturn(null);
        $this->validation->method('emptyNoteTag')->willReturn(null);
        $this->validation->method('emptyCreatedAt')->willReturn(null);
        $this->validation->method('emptyUserId')->willReturn(null);

        $createdAt = new DateTime();
        $notesModel = new NotesModel(1, 'note', 'sport', $createdAt, false, false);
        $repo->expects($this->once())
            ->method('createNewNoteQuery')
            ->willReturn($notesModel);

        $this->service = new NotesService($repo, $this->activeLog, $this->validation);
        $result = $this->service->createNote('note', 'sport', '2022-10-09 18:39:16', 1);
        $this->assertEquals(['success' => true], $result);
    }

    public function testSetImportantNote()
    {
        $repo = $this->createMock(NotesRepository::class);

        $this->validation->method('emptyNoteId')->willReturn(null);
        $this->validation->method('emptyImportantNote')->willReturn(null);
        $this->validation->method('emptyUserId')->willReturn(null);

        $createdAt = new DateTime();
        $notesModel = new NotesModel(1, 'note', 'sport', $createdAt, true, true);
        $repo->expects($this->once())
            ->method('setImportantNoteQuery')
            ->willReturn($notesModel);

        $this->service = new NotesService($repo, $this->activeLog, $this->validation);
        $result = $this->service->setImportantNote(1, true, 1);
        $this->assertEquals(['success' => true, 'important' => true], $result);
    }
}
