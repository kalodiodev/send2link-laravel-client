<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Contracts;

interface DeletableInterface
{
    /**
     * Delete Item by UUID
     */
    public function delete(string $uuid): void;
}
