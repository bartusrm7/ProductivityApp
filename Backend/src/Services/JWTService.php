<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Interfaces\JWTServiceInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService implements JWTServiceInterface
{
    public function generateToken()
    {
        $key = $_ENV['JWTKEY'];
        $payload = array(
            "iss" => "http://productivityapp.local",
            "aud" => "http://localhost:5173/sign-in",
            "iat" => time(),
            "nbf" => time(),
            "exp" => time() + (24 * 60 * 60)
        );
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }
    public function decodeToken(string $jwt)
    {
        $key = $_ENV['JWTKEY'];
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        return $decoded;
    }
}
