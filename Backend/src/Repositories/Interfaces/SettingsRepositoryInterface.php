<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface SettingsRepositoryInterface
{
    public function updateAvatarQuery(int $id, string $avatar);
}
