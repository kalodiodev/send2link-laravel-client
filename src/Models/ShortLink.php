<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Models;

readonly class ShortLink
{
    public function __construct(
        public string $uuid,
        public string $link,
        public string $destination,
        public bool $enabled,
        public ?string $expiresAt,
        public string $createdAt,
        public string $updatedAt
    ) {}

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getExpiresAt(): ?string
    {
        return $this->expiresAt;
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
