<?php

declare(strict_types=1);

namespace App\Auth;

use App\Models\AuthModel;
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
        $authModel = new AuthModel(1, '', '', '');

        $this->repository->method('userRegistrationQuery')->willReturn($authModel);

        $result = $this->service->userRegistration('', 'email@example.com', 'pass123');
        $this->assertEquals(['errors' => ['Name input field is empty']], $result);
    }

    public function testEmptyPasswordField()
    {
        $this->validation->method('emptyPasswordField')->willReturn('Password input field is empty');
        $authModel = new AuthModel(1, '', '', '');

        $this->repository->method('userRegistrationQuery')->willReturn($authModel);

        $result = $this->service->userRegistration('user123', 'email@example.com', '');
        $this->assertEquals(['errors' => ['Password input field is empty']], $result);
    }

    public function testNameLengthValidation()
    {
        $this->validation->method('nameLengthValidation')->willReturn('Name length must have at least 6 characters');
        $authModel = new AuthModel(1, '', '', '');

        $this->repository->method('userRegistrationQuery')->willReturn($authModel);

        $result = $this->service->userRegistration('user', 'email@example.com', 'pass123');
        $this->assertEquals(['errors' => ['Name length must have at least 6 characters']], $result);
    }

    public function testPasswordLengthValidation()
    {
        $this->validation->method('passwordLengthValidation')->willReturn('Password length must have at least 6 characters');
        $authModel = new AuthModel(1, '', '', '');

        $this->repository->method('userRegistrationQuery')->willReturn($authModel);

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

        $authModel = new AuthModel(1, 'user123', 'email@example.com', 'pass123');
        $repo->expects($this->once())
            ->method('userRegistrationQuery')
            ->with('user123', 'email@example.com', $this->anything())
            ->willReturn($authModel);


        $this->service = new AuthService($repo, $this->validation);
        $result = $this->service->userRegistration('user123', 'email@example.com', 'pass123');
        $this->assertEquals(['success' => true], $result);
    }

    public function testUserLogin()
    {
        $repo = $this->createMock(AuthRepository::class);

        $this->validation->method('emptyPasswordField')->willReturn(null);
        $this->validation->method('passwordLengthValidation')->willReturn(null);

        $hashPass = password_hash('pass123', PASSWORD_DEFAULT);
        $authModel = new AuthModel(1, 'user123', 'email@example.com', $hashPass);

        $repo->expects($this->once())
            ->method('userAlreadyExistsQuery')
            ->with('email@example.com')
            ->willReturn(['email' => 'email@example.com']);

        $repo->expects($this->once())
            ->method('userLoginQuery')
            ->with('email@example.com')
            ->willReturn($authModel);

        $this->service = new AuthService($repo, $this->validation);
        $result = $this->service->userLogin('email@example.com', 'pass123');
        $this->assertEquals(['success' => true, 'id' => 1, 'name' => 'user123'], $result);
    }
}
