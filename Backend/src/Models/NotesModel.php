<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;

class NotesModel
{
    public function __construct(private int $id, private string $name, private string $tag, private DateTime $createdAt, private bool $important) {}
    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getTag()
    {
        return $this->tag;
    }
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    public function getImportant()
    {
        return $this->important;
    }
}
