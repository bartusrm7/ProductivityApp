<?php

declare(strict_types=1);

namespace App\Tasks;

use App\Models\TasksModel;
use App\Repositories\TasksRepository;
use App\Services\TasksService;
use App\Validations\TasksValidation;
use DateTime;
use PHPUnit\Framework\TestCase;

class TasksTest extends TestCase
{
    private TasksService $service;
    private TasksRepository $repository;
    private TasksValidation $validation;

    public function setUp(): void
    {
        $this->repository = $this->createStub(TasksRepository::class);
        $this->validation = $this->createStub(TasksValidation::class);
        $this->service = new TasksService($this->repository, $this->validation);
    }

    public function testEmptyTaskName()
    {
        $this->validation->method('emptyTaskName')->willReturn('Name input field is empty');
        $this->repository->method('createNewTaskQuery')->willReturn(null);

        $result = $this->service->createNewTask('', '2026-04-24 19:40:00', 'low', 1);
        $this->assertEquals(['errors' => ['Name input field is empty']], $result);
    }

    public function testEmptyCreatedAt()
    {
        $this->validation->method('emptyCreatedAt')->willReturn('Created at time is not exists');
        $this->repository->method('createNewTaskQuery')->willReturn(null);


        $result = $this->service->createNewTask('reading', '', 'low', 1);
        $this->assertEquals(['errors' => ['Created at time is not exists']], $result);
    }

    public function testEmptyPriority()
    {
        $this->validation->method('emptyPriority')->willReturn('Priority input field is empty');
        $this->repository->method('createNewTaskQuery')->willReturn(null);


        $result = $this->service->createNewTask('reading', '2026-04-24 19:40:00', '', 1);
        $this->assertEquals(['errors' => ['Priority input field is empty']], $result);
    }

    public function testEmptyTaskStatus()
    {
        $this->validation->method('emptyTaskStatus')->willReturn('Status input field is empty');
        $this->repository->method('doneTaskQuery')->willReturn(null);


        $result = $this->service->doneTask(1, '', 1);
        $this->assertEquals(['errors' => ['Status input field is empty']], $result);
    }

    public function testEmptyUserId()
    {
        $this->validation->method('emptyUserId')->willReturn('UserID is not exists');
        $this->repository->method('createNewTaskQuery')->willReturn(null);


        $result = $this->service->createNewTask('reading', '2026-04-24 19:40:00', 'low', 0);
        $this->assertEquals(['errors' => ['UserID is not exists']], $result);
    }
    
    public function testCreateNewTask()
    {
        $repo = $this->createMock(TasksRepository::class);

        $this->validation->method('emptyTaskName')->willReturn(null);
        $this->validation->method('emptyCreatedAt')->willReturn(null);
        $this->validation->method('emptyPriority')->willReturn(null);
        $this->validation->method('emptyUserId')->willReturn(null);

        $createdAt = new DateTime;
        $tasksModel = new TasksModel(1, 'reading', $createdAt, 'low', 'todo');
        $repo->expects($this->once())
            ->method('createNewTaskQuery')
            ->willReturn($tasksModel);

        $this->service = new TasksService($repo, $this->validation);
        $result = $this->service->createNewTask('reading', '2026-04-24 19:40:00', 'low', 1);
        $this->assertEquals(['success' => true], $result);
    }

    public function testDoneTask()
    {
        $repo = $this->createMock(TasksRepository::class);

        $this->validation->method('emptyTaskId')->willReturn(null);
        $this->validation->method('emptyTaskStatus')->willReturn(null);
        $this->validation->method('emptyUserId')->willReturn(null);

        $createdAt = new DateTime;
        $tasksModel = new TasksModel(1, 'reading', $createdAt, 'low', 'todo');
        $repo->expects($this->once())
            ->method('doneTaskQuery')
            ->willReturn($tasksModel);

        $this->service = new TasksService($repo, $this->validation);
        $result = $this->service->doneTask(1, 'todo', 1);
        $this->assertEquals(['success' => true], $result);
    }

    public function testEditTask()
    {
        $repo = $this->createMock(TasksRepository::class);

        $this->validation->method('emptyTaskId')->willReturn(null);
        $this->validation->method('emptyTaskStatus')->willReturn(null);
        $this->validation->method('emptyUserId')->willReturn(null);

        $createdAt = new DateTime;
        $tasksModel = new TasksModel(1, 'reading', $createdAt, 'low', 'todo');
        $repo->expects($this->once())
            ->method('editTaskQuery')
            ->willReturn($tasksModel);

        $this->service = new TasksService($repo, $this->validation);
        $result = $this->service->editTask(1, 'reading', 'low', 1);
        $this->assertEquals(['success' => true], $result);
    }

    public function testDeleteTask()
    {
        $repo = $this->createMock(TasksRepository::class);

        $this->validation->method('emptyTaskId')->willReturn(null);
        $this->validation->method('emptyUserId')->willReturn(null);

        $createdAt = new DateTime;
        $tasksModel = new TasksModel(1, 'reading', $createdAt, 'low', 'todo');
        $repo->expects($this->once())
            ->method('deleteTaskQuery')
            ->willReturn($tasksModel);

        $this->service = new TasksService($repo, $this->validation);
        $result = $this->service->deleteTask(1, 1);
        $this->assertEquals(['success' => true], $result);
    }
}
