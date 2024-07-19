<?php

namespace Kalodiodev\Send2Link\Models;

class ShortLink
{
    private string $uuid;
    private string $link;
    private string $destination;
    private bool $enabled;
    private string $createdAt;
    private string $updatedAt;

    /**
     * @param string $uuid
     * @param string $link
     * @param string $destination
     * @param bool $enabled
     * @param string $createdAt
     * @param string $updatedAt
     */
    public function __construct(string $uuid, string $link, string $destination, bool $enabled, string $createdAt, string $updatedAt)
    {
        $this->uuid = $uuid;
        $this->link = $link;
        $this->destination = $destination;
        $this->enabled = $enabled;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

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

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }
}
