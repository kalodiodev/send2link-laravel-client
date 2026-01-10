<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Models;

readonly class Domain
{
    public function __construct(
        public ?string $name = null,
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }
}
