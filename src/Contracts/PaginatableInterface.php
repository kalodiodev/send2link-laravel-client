<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Contracts;

interface PaginatableInterface
{
    /**
     * Paginate results
     */
    public function paginate(int $page, int $size = 25): static;
}
