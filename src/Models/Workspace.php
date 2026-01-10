<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Models;

readonly class Workspace
{
    public function __construct(
        public ?string $name = null,
        public string $slug,
        public ?string $workspaceRole = null,
    ) {}

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getWorkspaceRole(): ?string
    {
        return $this->workspaceRole;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
