<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Interfaces\JWTServiceInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService implements JWTServiceInterface
{
    public function generateToken(string $email, int $userId)
    {
        $key = $_ENV['JWTKEY'];
        $payload = array(
            "iss"       => "http://productivityapp.local",
            "aud"       => "http://localhost:5173/sign-in",
            "iat"       => time(),
            "nbf"       => time(),
            "exp"       => time() + (24 * 60 * 60),
            "user_id"   => $userId,
            "email"     => $email,
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

    public function getToken()
    {
        $authorization = $_SERVER['HTTP_AUTHORIZATION'];
        $jwt = str_replace('Bearer ', '', $authorization);
        return $jwt;
    }

    public function getUserIdFromJWT()
    {
        $jwt = $this->getToken();
        $decoded = $this->decodeToken($jwt);
        return $decoded->user_id;
    }


    public function getUserEmailFromJWT()
    {
        $jwt = $this->getToken();
        $decoded = $this->decodeToken($jwt);
        $email = $decoded->email;
        return [
            'success' => true,
            'email'    => $email
        ];
    }
}
