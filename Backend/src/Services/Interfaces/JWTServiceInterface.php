<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

interface JWTServiceInterface
{
    public function generateToken();
    public function decodeToken(string $jwt);
}
