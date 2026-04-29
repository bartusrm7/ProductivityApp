<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;

class HabitsModel
{
    public function __construct(private int $id, private string $name, private string $description, private DateTime $createdAt) {}
    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
