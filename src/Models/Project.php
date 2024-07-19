<?php

namespace Kalodiodev\Send2Link\Models;

class Project
{
    private string $uuid;
    private string $name;
    private string|null $description;
    private string $createdAt;
    private string $updatedAt;

    public function __construct(string $uuid, string $name, string|null $description, string $createdAt, string $updatedAt)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }
}
