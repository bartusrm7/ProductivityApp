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
    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }
    public function getPriority()
    {
        return $this->priority;
    }
    public function setPriority(string $priority)
    {
        $this->priority = $priority;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus(string $status)
    {
        $this->status = $status;
    }
}
