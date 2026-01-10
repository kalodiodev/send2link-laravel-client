<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Response;

use Illuminate\Support\Collection;

/**
 * Items page response
 *
 * @template T
 */
readonly class PageResponse
{
    /**
     * @param int $status
     * @param int $page
     * @param int $pageSize
     * @param int $pagesCount
     * @param int $totalElements
     * @param bool $isFirst
     * @param bool $isLast
     * @param Collection<T> $items
     */
    public function __construct(
        private int        $status,
        private int        $page,
        private int        $pageSize,
        private int        $pagesCount,
        private int        $totalElements,
        private bool       $isFirst,
        private bool       $isLast,
        private Collection $items
    ) {}

    /**
     * @return Collection<T> collection of T model
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @return int status code
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return int current page number
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int the page size
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * @return int total pages
     */
    public function getPagesCount(): int
    {
        return $this->pagesCount;
    }

    /**
     * @return int total elements
     */
    public function getTotalElements(): int
    {
        return $this->totalElements;
    }

    /**
     * @return bool whether this is the last page
     */
    public function isLast(): bool
    {
        return $this->isLast;
    }

    /**
     * @return bool whether this is the first page
     */
    public function isFirst(): bool
    {
        return $this->isFirst;
    }
}
