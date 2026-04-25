<?php

declare(strict_types=1);

namespace App\Tasks;

use App\Repositories\TasksRepository;
use App\Services\TasksService;
use App\Validations\TasksValidation;
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

        $result = $this->service->createNewTask('reading', '2026-04-24 19:40:00', 'low', 1);
        $this->assertEquals(['errors' => ['Name input field is empty']], $result);
    }
}
