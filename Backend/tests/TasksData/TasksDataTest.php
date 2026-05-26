<?php

declare(strict_types=1);

namespace App\TasksData;

use App\Repositories\ActivityLogRepository;
use App\Repositories\TasksDataRepository;
use App\Services\TasksDataService;
use App\Validations\TasksDataValidation;
use DateTime;
use Override;
use PHPUnit\Framework\TestCase;

class TasksDataTest extends TestCase
{
    private TasksDataService $service;
    private TasksDataRepository $repository;
    private ActivityLogRepository $activeLog;
    private TasksDataValidation $validation;

    #[Override]
    public function setUp(): void
    {
        $this->repository = $this->createStub(TasksDataRepository::class);
        $this->activeLog = $this->createStub(ActivityLogRepository::class);
        $this->validation = $this->createStub(TasksDataValidation::class);
        $this->service = new TasksDataService($this->repository, $this->activeLog, $this->validation);
    }

    public function testEmptyDeadlineDayValue()
    {
        $this->validation->method('emptyDeadline')->willReturn('Deadline day is not exists');
        $this->repository->method('setDeadlineDateQuery')->willReturn(null);

        $result = $this->service->setDeadline('', 1);
        $this->assertEquals(['errors' => ['Deadline day is not exists']], $result);
    }

    public function testEmptyTaskId()
    {
        $this->validation->method('emptyTaskId')->willReturn('Task ID is not exists');
        $this->repository->method('setDeadlineDateQuery')->willReturn(null);

        $result = $this->service->setDeadline('2022-05-22', 0);
        $this->assertEquals(['errors' => ['Task ID is not exists']], $result);
    }

    public function testSetDeadlineDay()
    {
        $repo = $this->createMock(TasksDataRepository::class);

        $this->validation->method('emptyDeadline')->willReturn(null);
        $this->validation->method('emptyTaskId')->willReturn(null);

        $deadlineDate = new DateTime()->format('Y-m-d 00:00:00');
        $repo->expects($this->once())
            ->method('setDeadlineDateQuery')
            ->willReturn($deadlineDate, 0);

        $this->service = new TasksDataService($repo, $this->activeLog, $this->validation);
        $result = $this->service->setDeadline('2022-05-22', 1);
        $this->assertEquals(['success' => true], $result);
    }
}
