<?php

declare(strict_types=1);

namespace App\Services\Interfaces;


interface SettingsServiceInterface
{
    public function updateAvatar(int $id, array $avatar);
}
