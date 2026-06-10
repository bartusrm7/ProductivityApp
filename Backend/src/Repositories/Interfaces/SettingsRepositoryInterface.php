<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface SettingsRepositoryInterface
{
    public function updateAvatarQuery(int $id, array $avatar);
    public function updateUserNameQuery(string $name, int $id);
    public function setReminderNotificationsQuery(int $id, int $reminders);
}
