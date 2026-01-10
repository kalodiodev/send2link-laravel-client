<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Contracts;

use Kalodiodev\Send2Link\Response\ItemResponse;

interface FindableInterface
{
    /**
     * Find a single item by its UUID
     */
    public function find(string $uuid): ItemResponse;
}
