<?php

namespace Kalodiodev\Send2Link\Response;

use Illuminate\Support\Collection;

class ProjectsResponse
{
    public Collection $projects;
    private int $status;
    private int $page;
    private int $pageSize;
    private int $pagesCount;
    private bool $isLast;
    private bool $isFirst;

    public function __construct(int $status, int $page, $pageSize, $pagesCount, $isFirst, $isLast, Collection $projects)
    {
        $this->status = $status;
        $this->projects = $projects;
        $this->page = $page;
        $this->pageSize = $pageSize;
        $this->pagesCount = $pagesCount;
        $this->isFirst = $isFirst;
        $this->isLast = $isLast;
    }

    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getPagesCount(): int
    {
        return $this->pagesCount;
    }

    public function isLast(): bool
    {
        return $this->isLast;
    }

    public function isFirst(): bool
    {
        return $this->isFirst;
    }
}
