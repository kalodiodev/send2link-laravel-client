<?php

namespace Kalodiodev\Send2Link\Queries;

use Illuminate\Auth\AuthenticationException;
use Kalodiodev\Send2Link\Models\Project;
use Kalodiodev\Send2Link\Response\ProjectResponse;
use Kalodiodev\Send2Link\Response\ProjectsResponse;

class ProjectsQuery extends QueryBuilder
{
    protected string $apiUrl = '/api/v1/projects';
    protected string $resultsKey = 'content';

    /**
     * @throws AuthenticationException
     */
    public function getAll(): ProjectsResponse
    {
        $response = $this->performGet();

        if ($response->successful()) {
            $projects = $this->parseItems($response->json($this->resultsKey));

            return new ProjectsResponse(
                $response->status(),
                $response->json('page'),
                $response->json('size'),
                $response->json('totalPages'),
                $response->json('first'),
                $response->json('last'),
                $projects
            );
        }

        return new ProjectsResponse($response->status(),0,0,0, true, true, collect());
    }

    /**
     * @throws AuthenticationException
     */
    public function getByUuid(string $uuid): ProjectResponse
    {
        $this->url = $this->client->getBaseUrl() . $this->apiUrl . '/' . $uuid;

        $response = $this->client->get($this->url);

        $project = $this->parseItem($response->json());

        return new ProjectResponse($response->status(), $project);
    }

    /**
     * @throws AuthenticationException
     */
    public function create(string $name, string $description = null): ProjectResponse
    {
        $this->url = $this->client->getBaseUrl() . $this->apiUrl;

        $response = $this->client->post($this->url, [
            'name' => $name,
            'description' => $description
        ]);

        $project = $this->parseItem($response->json());

        return new ProjectResponse($response->status(), $project);
    }

    /**
     * @throws AuthenticationException
     */
    public function update(string $uuid, string $name, string|null $description = null): void
    {
        $this->url = $this->client->getBaseUrl() . $this->apiUrl . '/' . $uuid;

        $this->client->patch($this->url, [
            'name' => $name,
            'description' => $description
        ]);
    }

    /**
     * @throws \Exception
     */
    public function delete(string $uuid): void
    {
        $this->url = $this->client->getBaseUrl() . $this->apiUrl . '/' . $uuid;

        $this->client->delete($this->url);
    }

    protected function parseItem(array $item): mixed
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
