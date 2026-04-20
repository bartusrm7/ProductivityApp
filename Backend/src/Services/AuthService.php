<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AuthRepository;
use App\Validations\AuthValidation;
use App\Services\Interfaces\AuthServiceInterface;

class AuthService implements AuthServiceInterface
{
    private AuthRepository $repository;
    private AuthValidation $validation;

    public function __construct(AuthRepository $repository, AuthValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function userRegistration(string $name, string $email, string $password): array
    {
        $errors = [];
        if ($error = $this->validation->emptyNameField($name)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->nameLengthValidation($name)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->emptyPasswordField($password)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->passwordLengthValidation($password)) {
            $errors[] = $error;
        }
        $userExists = $this->repository->userAlreadyExistsQuery($email);
        if ($userExists) {
            return ['errors' => ['User with this email is already exists.']];
        }

        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->repository->userRegistrationQuery($name, $email, $hashPassword);
            return ['success' => true];
        }
    }

    public function userLogin(string $email, string $password): array
    {
        $errors = [];
        if ($error = $this->validation->emptyPasswordField($password)) {
            $errors[] = $error;
        }
        if ($error = $this->validation->passwordLengthValidation($password)) {
            $errors[] = $error;
        }
        $userExists = $this->repository->userAlreadyExistsQuery($email);
        if (!$userExists) {
            return ['errors' => ['User with this email is not exists.']];
        }

        if (!empty($errors)) {
            return ['errors' => $errors];
        } else {
            $user = $this->repository->userLoginQuery($email);

            if (!password_verify($password, $user->getPassword())) {
                return ['errors' => ['Password is incorrect.']];
            }
            return ['success' => true];
        }
    }
}
