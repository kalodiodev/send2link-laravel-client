<?php

namespace Kalodiodev\Send2Link\Response;

use Illuminate\Support\Collection;
use Kalodiodev\Send2Link\Models\Project;

class ProjectsResponse extends ItemsResponse
{
    /**
     * @return Collection<Project>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }
}
