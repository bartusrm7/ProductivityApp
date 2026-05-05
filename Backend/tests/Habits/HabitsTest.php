<?php

declare(strict_types=1);

namespace App\Habits;

use App\Models\HabitsModel;
use App\Repositories\HabitsRepository;
use App\Services\HabitsService;
use App\Validations\HabitsValidation;
use DateTime;
use PHPUnit\Framework\TestCase;

class HabitsTest extends TestCase
{
    private HabitsService $service;
    private HabitsRepository $repository;
    private HabitsValidation $validation;


    public function setUp(): void
    {
        $this->repository = $this->createStub(HabitsRepository::class);
        $this->validation = $this->createStub(HabitsValidation::class);
        $this->service = new HabitsService($this->repository, $this->validation);
    }

    public function testEmptyHabitName()
    {
        $this->validation->method('emptyHabitName')->willReturn('Name input field is empty');
        $this->repository->method('newHabitQuery')->willReturn(null);

        $result = $this->service->newHabit('', '2022-10-09 18:39:16', 1);
        $this->assertEquals(['errors' => ['Name input field is empty']], $result);
    }

    // public function testEmptyHabitDescription()
    // {
    //     $this->validation->method('emptyHabitDescription')->willReturn('Description input field is empty');
    //     $this->repository->method('newHabitQuery')->willReturn(null);

    //     $result = $this->service->newHabit('', '2022-10-09 18:39:16', 1);
    //     $this->assertEquals(['errors' => ['Name input field is empty']], $result);
    // }

    public function testEmptyCreatedAt()
    {
        $this->validation->method('emptyCreatedAt')->willReturn('Created at time is not exists');
        $this->repository->method('newHabitQuery')->willReturn(null);

        $result = $this->service->newHabit('reading book everyday', '', 1);
        $this->assertEquals(['errors' => ['Created at time is not exists']], $result);
    }

    public function testEmptyUserId()
    {
        $this->validation->method('emptyUserId')->willReturn('UserID is not exists');
        $this->repository->method('newHabitQuery')->willReturn(null);

        $result = $this->service->newHabit('reading book everyday', '2022-10-09 18:39:16', 0);
        $this->assertEquals(['errors' => ['UserID is not exists']], $result);
    }

    public function testCreateNewHabit()
    {
        $repo = $this->createMock(HabitsRepository::class);

        $this->validation->method('emptyHabitName')->willReturn(null);
        $this->validation->method('emptyCreatedAt')->willReturn(null);
        $this->validation->method('emptyUserId')->willReturn(null);

        $createdAt = new DateTime();
        $habitsModel = new HabitsModel(1, 'reading book everyday', '', $createdAt, '');
        $repo->expects($this->once())
            ->method('newHabitQuery')
            ->willReturn($habitsModel);

        $this->service = new HabitsService($repo, $this->validation);
        $result = $this->service->newHabit('reading book everyday', '2022-10-09 18:39:16', 1);
        $this->assertEquals(['success' => true], $result);
    }

    public function testEditHabit()
    {
        $repo = $this->createMock(HabitsRepository::class);

        $this->validation->method('emptyHabitId')->willReturn(null);
        $this->validation->method('emptyHabitName')->willReturn(null);
        $this->validation->method('emptyHabitDescription')->willReturn(null);
        $this->validation->method('emptyUserId')->willReturn(null);

        $habitsModel = new HabitsModel(1, 'reading book everyday', '', new DateTime, '');
        $repo->expects($this->once())
            ->method('editHabitQuery')
            ->willReturn($habitsModel);

        $this->service = new HabitsService($repo, $this->validation);
        $result = $this->service->editHabit(1, 'reading book everyday', '', 1);
        $this->assertEquals(['success' => true], $result);
    }
}
