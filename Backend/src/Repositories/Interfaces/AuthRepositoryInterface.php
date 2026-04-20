<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\AuthModel;

interface AuthRepositoryInterface
{
    public function userRegistrationQuery(string $name, string $email, string $password): AuthModel;
    public function userAlreadyExistsQuery(string $email);
    public function userLoginQuery(string $email): ?AuthModel;
}
