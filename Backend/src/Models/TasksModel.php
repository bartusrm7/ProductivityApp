<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;

class TasksModel
{
    public function __construct(private int $id, private string $name, private DateTime $createdAt, private string $priority, private string $status) {}

    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    public function getPriority()
    {
        return $this->priority;
    }
    public function getStatus()
    {
        return $this->status;
    }
}
