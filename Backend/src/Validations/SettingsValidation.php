<?php

declare(strict_types=1);

namespace App\Validations;

class SettingsValidation
{
    public function emptyUserId(int $id)
    {
        if (empty($id)) {
            return 'User ID is not exists';
        }
    }
    public function emptyNameField(string $name)
    {
        if (empty($name)) {
            return 'Name input field is empty';
        }
    }
    public function emptyPasswordField(string $password)
    {
        if (empty($password)) {
            return 'Password input field is empty';
        }
    }
    public function emptyAvatarField(array $avatar)
    {
        if (empty($avatar)) {
            return 'Avatar data does not available';
        }
    }
}
