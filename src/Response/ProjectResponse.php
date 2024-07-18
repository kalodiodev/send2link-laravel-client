<?php

namespace Kalodiodev\Send2Link\Response;

use Kalodiodev\Send2Link\Models\Project;

class ProjectResponse
{
    private int $status;
    private Project $project;

    public function __construct(int $status, Project $project)
    {
        $this->status = $status;
        $this->project = $project;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getProject(): Project
    {
        return $this->project;
    }
}
