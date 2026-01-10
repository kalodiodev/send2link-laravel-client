<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Contracts;

interface ResourceInterface
{
    public function queryUrl(): string;
}
