<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AuthRepository;
use App\Validations\AuthValidation;

class AuthService
{
    private AuthRepository $repository;
    private AuthValidation $validation;

    public function __construct(AuthRepository $repository, AuthValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function userRegistration($name, $email, $password)
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

        if (!empty($errors)) {
            return $errors;
        } else {
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            return $this->repository->userRegistrationQuery($name, $email, $hashPassword);
        }
    }
}
