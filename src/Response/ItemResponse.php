<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Response;

/**
 * Single Item Response
 *
 * @template T
 */
readonly class ItemResponse
{
    /**
     * @param int $status
     * @param T $item
     */
    public function __construct(
        private int $status,
        private mixed $item
    ) {}

    /**
     * @return int status code
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return T model
     */
    public function getItem(): mixed
    {
        return $this->item;
    }
}
