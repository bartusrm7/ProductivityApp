<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Models\AuthModel;

interface AuthServiceInterface
{
    public function userRegistration(string $name, string $email, string $password): array;
    public function userLogin(string $email, string $password): array;
}
