<?php

declare(strict_types=1);

namespace App\Auth;

use App\Repositories\AuthRepository;
use App\Services\AuthService;
use App\Validations\AuthValidation;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    private AuthService $service;
    private AuthRepository $repository;
    private AuthValidation $validation;

    public function setUp(): void
    {
        $this->repository = $this->createStub(AuthRepository::class);
        $this->validation = $this->createStub(AuthValidation::class);
        $this->service = new AuthService($this->repository, $this->validation);
    }

    public function testEmptyNameField()
    {
        $this->validation->method('emptyNameField')->willReturn('Name input field is empty');
        $this->repository->method('userRegistrationQuery')->willReturn(null);

        $result = $this->service->userRegistration('', 'email@example.com', 'pass123');
        $this->assertEquals(['errors' => ['Name input field is empty']], $result);
    }

    public function testEmptyPasswordField()
    {
        $this->validation->method('emptyPasswordField')->willReturn('Password input field is empty');
        $this->repository->method('userRegistrationQuery')->willReturn(null);

        $result = $this->service->userRegistration('user123', 'email@example.com', '');
        $this->assertEquals(['errors' => ['Password input field is empty']], $result);
    }

    public function testNameLengthValidation()
    {
        $this->validation->method('nameLengthValidation')->willReturn('Name length must have at least 6 characters');
        $this->repository->method('userRegistrationQuery')->willReturn(null);

        $result = $this->service->userRegistration('user', 'email@example.com', 'pass123');
        $this->assertEquals(['errors' => ['Name length must have at least 6 characters']], $result);
    }

    public function testPasswordLengthValidation()
    {
        $this->validation->method('passwordLengthValidation')->willReturn('Password length must have at least 6 characters');
        $this->repository->method('userRegistrationQuery')->willReturn(null);

        $result = $this->service->userRegistration('user123', 'email@example.com', 'pass');
        $this->assertEquals(['errors' => ['Password length must have at least 6 characters']], $result);
    }

    public function testUserRegistration()
    {
        $repo = $this->createMock(AuthRepository::class);

        $this->validation->method('emptyNameField')->willReturn(null);
        $this->validation->method('emptyPasswordField')->willReturn(null);
        $this->validation->method('nameLengthValidation')->willReturn(null);
        $this->validation->method('passwordLengthValidation')->willReturn(null);
        $repo->expects($this->once())
            ->method('userRegistrationQuery')
            ->with('user123', 'email@example.com', $this->anything())
            ->willReturn(['success' => true]);


        $this->service = new AuthService($repo, $this->validation);
        $result = $this->service->userRegistration('user123', 'email@example.com', 'pass123');
        $this->assertEquals(['success' => true], $result);
    }
}