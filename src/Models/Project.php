<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Models;

readonly class Project
{
    public function __construct(
        public string  $uuid,
        public string  $name,
        public ?string $description,
        public string  $createdAt,
        public string  $updatedAt
    ) {}

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
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
