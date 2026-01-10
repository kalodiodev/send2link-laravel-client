<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Models;

readonly class Account
{
    public function __construct(
        public string $email,
        public string $firstName,
        public string $lastName,
        public string $createdAt,
        public int $projectsCount,
        public int $shortLinksCount
    ) {}

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getProjectsCount(): int
    {
        return $this->projectsCount;
    }

    public function getShortLinksCount(): int
    {
        return $this->shortLinksCount;
    }

    public function getFullName(): string
    {
        return trim($this->firstName . ' ' . $this->lastName);
    }
}

