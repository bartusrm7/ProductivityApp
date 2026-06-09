<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

interface JWTServiceInterface
{
    public function generateToken(string $email, int $userId);
    public function decodeToken(string $jwt);
    public function getToken();
    public function getUserIdFromJWT();
}
