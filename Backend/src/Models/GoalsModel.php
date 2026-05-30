<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;

class GoalsModel
{
    public function __construct(private int $id, private string $name, private string $description, private string $status, private string $type, private float $progress,  private DateTime $createdAt, private DateTime $deadline) {}
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
    public function getStatus()
    {
        return $this->status;
    }
    public function getType()
    {
        return $this->type;
    }
    public function getProgress()
    {
        return $this->progress;
    }
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    public function getDeadline()
    {
        return $this->deadline;
    }
}
