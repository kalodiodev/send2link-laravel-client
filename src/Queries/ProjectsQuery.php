<?php

namespace Kalodiodev\Send2Link\Queries;

use Illuminate\Http\Client\RequestException;
use Kalodiodev\Send2Link\Models\Project;
use Kalodiodev\Send2Link\Response\ItemResponse;
use Kalodiodev\Send2Link\Response\PageResponse;

class ProjectsQuery extends QueryBuilder
{
    protected string $apiUrl = '/api/v1/projects';
    protected string $resultsKey = 'content';

    /**
     * Get all Projects
     *
     * @return PageResponse<Project>
     * @throws RequestException
     */
    public function getAll(): PageResponse
    {
        return parent::getAll();
    }

    /**
     * Create Project
     *
     * @return ItemResponse<Project>
     * @throws RequestException
     */
    public function create(string $name, string $description = null): ItemResponse
    {
        return $this->postRequest([
            'name' => $name,
            'description' => $description
        ]);
    }

    /**
     * Update Project
     *
     * @throws RequestException
     */
    public function update(string $uuid, string $name, string|null $description = null): void
    {
        $this->patchRequest($uuid, [
            'name' => $name,
            'description' => $description
        ]);
    }

    protected function parseItem(array $item): Project
    {
        return new Project(
            $item['uuid'],
            $item['name'],
            $item['description'],
            $item['createdAt'],
            $item['updatedAt']
        );
    }
}
