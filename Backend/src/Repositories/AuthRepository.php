<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\AuthModel;
use PDO;

class AuthRepository
{
    private PDO $pdo;
    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function userRegistrationQuery($name, $email, $password)
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $stmt->execute([':name' => $name, ':email' => $email, ':password' => $password]);
        $id = (int) $this->pdo->lastInsertId();

        return new AuthModel($id, $name, $email, $password);
    }

    public function userAlreadyExistsQuery($email)
    {
        $stmt = $this->pdo->prepare('SELECT email FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function userLoginQuery($email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();

        if (!$row) return null;

        return new AuthModel(
            $row['id'],
            $row['name'],
            $email,
            $row['password']
        );
    }
}
