<?php

namespace Kalodiodev\Send2Link\Response;

/**
 * Single Item Response
 *
 * @template T
 */
class ItemResponse
{
    private int $status;
    private $item;

    /**
     * @param int $status
     * @param T $item
     */
    public function __construct(int $status, $item)
    {
        $this->status = $status;
        $this->item = $item;
    }

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
    public function getItem()
    {
        return $this->item;
    }
}
