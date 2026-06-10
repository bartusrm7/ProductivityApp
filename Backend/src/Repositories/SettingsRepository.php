<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\UserModel;
use App\Repositories\Interfaces\SettingsRepositoryInterface;
use PDO;

class SettingsRepository implements SettingsRepositoryInterface
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function updateAvatarQuery(int $id, array $avatar)
    {
        $avatarFileName = uniqid() . '.' . pathinfo($avatar['name'], PATHINFO_EXTENSION);
        $avatarTmp = $avatar['tmp_name'];
        $newAvatarFile = 'uploads/' . $avatarFileName;
        if (!move_uploaded_file($avatarTmp, $newAvatarFile)) {
            return [
                'success' => false,
                'errors'  => 'Upload file failed'
            ];
        };

        $stmt = $this->pdo->prepare('UPDATE users SET avatar = :avatar WHERE id = :id');
        $stmt->execute([':id' => $id, ':avatar' => $newAvatarFile]);

        return new UserModel($id, '', '', $newAvatarFile);
    }

    public function displayUserAvatarQuery(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT avatar FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function updateUserNameQuery(string $name, int $id)
    {
        $stmt = $this->pdo->prepare('UPDATE users SET name = :name WHERE id = :id');
        $stmt->execute([':name' => $name, ':id' => $id]);

        return new UserModel($id, $name, '', '');
    }
}
