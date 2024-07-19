<?php

namespace Kalodiodev\Send2Link\Response;

use Illuminate\Support\Collection;

/**
 * Items page response
 *
 * @template T
 */
class PageResponse
{
    protected Collection $items;
    private int $status;
    private int $page;
    private int $pageSize;
    private int $pagesCount;
    private bool $isLast;
    private bool $isFirst;

    /**
     * @param int $status
     * @param int $page
     * @param int $pageSize
     * @param int $pagesCount
     * @param bool $isFirst
     * @param bool $isLast
     * @param Collection<T> $items
     */
    public function __construct(int $status, int $page, int $pageSize, int $pagesCount, bool $isFirst, bool $isLast, Collection $items)
    {
        $this->status = $status;
        $this->items = $items;
        $this->page = $page;
        $this->pageSize = $pageSize;
        $this->pagesCount = $pagesCount;
        $this->isFirst = $isFirst;
        $this->isLast = $isLast;
    }

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
