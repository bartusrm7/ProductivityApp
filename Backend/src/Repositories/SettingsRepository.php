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

    public function updateAvatarQuery(int $id, string $avatar)
    {
        $stmt = $this->pdo->prepare('UPDATE users SET avatar = :avatar WHERE id = :id');
        $stmt->execute([':id' => $id, ':avatar' => $avatar]);

        return new UserModel($id, '', '', $avatar);
    }
}
