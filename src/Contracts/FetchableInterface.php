<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Contracts;

interface FetchableInterface
{
    /**
     * Fetch all items (paginated or non-paginated)
     */
    public function all(): mixed;
}
