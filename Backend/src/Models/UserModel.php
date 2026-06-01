<?php

declare(strict_types=1);

namespace App\Models;

class UserModel
{
    public function __construct(private int $id, private string $name, private string $password, private string $avatar) {}

    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getAvatar()
    {
        return $this->avatar;
    }
}
