<?php

declare(strict_types=1);

namespace App\Validations;

class AuthValidation
{
    public function emptyUserId(int $id)
    {
        if (empty($id)) {
            return 'User ID does not exists';
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
    public function nameLengthValidation(string $name)
    {
        if (strlen($name) < 6) {
            return 'Name length must have at least 6 characters';
        }
    }
    public function passwordLengthValidation(string $password)
    {
        if (strlen($password) < 6) {
            return 'Password length must have at least 6 characters';
        }
    }
}
