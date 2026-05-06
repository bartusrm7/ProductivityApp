<?php

declare(strict_types=1);

namespace App\HabitsData;

use App\Models\HabitsDataModel;
use App\Repositories\HabitsDataRepository;
use App\Services\HabitsDataService;
use App\Validations\HabitsDataValidation;
use DateTime;
use PHPUnit\Framework\TestCase;

class HabitsDataTest extends TestCase
{
    private HabitsDataService $service;
    private HabitsDataRepository $repository;
    private HabitsDataValidation $validation;

    public function setUp(): void
    {
        $this->repository = $this->createStub(HabitsDataRepository::class);
        $this->validation = $this->createStub(HabitsDataValidation::class);
        $this->service = new HabitsDataService($this->repository, $this->validation);
    }

    public function testEmptyHabitDataId()
    {
        $this->validation->method('emptyHabitDataId')->willReturn('Habit data ID does not exist');
        $this->repository->method('setHabitThisDayDoneQuery')->willReturn(null);

        $result = $this->service->setHabitThisDayDone(0, '2022-10-09 18:39:16');
        $this->assertEquals(['errors' => ['Habit data ID does not exist']], $result);
    }

    public function testEmptyStreakDays()
    {
        $this->validation->method('emptyStreakDays')->willReturn('Streak days value is empty');
        $this->repository->method('countCurrentStreakDaysQuery')->willReturn(null);

        $result = $this->service->countCurrentStreakDays(1, 0);
        $this->assertEquals(['errors' => ['Streak days value is empty']], $result);
    }

    public function testEmptyCheckCurrentDay()
    {
        $this->validation->method('emptyCheckCurrentDay')->willReturn('Check current day is empty');
        $this->repository->method('setHabitThisDayDoneQuery')->willReturn(null);

        $result = $this->service->setHabitThisDayDone(1, '');
        $this->assertEquals(['errors' => ['Check current day is empty']], $result);
    }

    public function testEmptyAmountDaysDone()
    {
        $this->validation->method('emptyAmountDaysDone')->willReturn('Amount days done is empty');
        $this->repository->method('countAmountDaysDoneQuery')->willReturn(null);

        $result = $this->service->countAmountDaysDone(1, 0);
        $this->assertEquals(['errors' => ['Amount days done is empty']], $result);
    }

    public function testSetHabitThisDayDone()
    {
        $repo = $this->createMock(HabitsDataRepository::class);

        $this->validation->method('emptyHabitDataId')->willReturn(null);
        $this->validation->method('emptyCheckCurrentDay')->willReturn(null);

        $repo->expects($this->once())
            ->method('setHabitThisDayDoneQuery')
            ->with(
                $this->equalTo(1),
                $this->isInstanceOf(DateTime::class)
            );

        $this->service = new HabitsDataService($repo, $this->validation);
        $result = $this->service->setHabitThisDayDone(1, '2022-10-09 18:39:16');
        $this->assertEquals(['success' => true], $result);
    }

    // public function testCountCurrentStreakDaysQuery()
    // {
    //     $repo = $this->createMock(HabitsDataRepository::class);

    //     $this->validation->method('emptyHabitDataId')->willReturn(null);
    //     $this->validation->method('emptyStreakDays')->willReturn(null);

    //     $repo->expects($this->once())
    //         ->method('countCurrentStreakDaysQuery')
    //         ->with(1, 1);

    //     $this->service = new HabitsDataService($repo, $this->validation);
    //     $result = $this->service->countCurrentStreakDays(1, 1);
    //     $this->assertEquals(['success' => true], $result);
    // }

    public function testCountAmountDaysDoneQuery()
    {
        $repo = $this->createMock(HabitsDataRepository::class);

        $this->validation->method('emptyHabitDataId')->willReturn(null);
        $this->validation->method('emptyAmountDaysDone')->willReturn(null);

        $repo->expects($this->once())
            ->method('countAmountDaysDoneQuery')
            ->with(1, 1 + 1);

        $this->service = new HabitsDataService($repo, $this->validation);
        $result = $this->service->countAmountDaysDone(1, 1);
        $this->assertEquals(['success' => true], $result);
    }
}
