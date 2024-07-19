<?php

namespace Kalodiodev\Send2Link\Queries;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Client\Response;
use Kalodiodev\Send2Link\Models\Project;
use Kalodiodev\Send2Link\Response\ProjectsResponse;

class ProjectsQuery extends QueryBuilder
{
    protected string $apiUrl = '/api/v1/projects';
    protected string $resultsKey = 'content';

    /**
     * @throws AuthenticationException
     */
    public function get(): ProjectsResponse
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
    public function create(string $name, string $description): Response
    {
        $this->url = $this->client->getBaseUrl() . $this->apiUrl;

        return $this->client->post($this->url, [
            'name' => $name,
            'description' => $description
        ]);
    }

    /**
     * @throws AuthenticationException
     */
    public function update(string $uuid, string $name, string $description): Response
    {
        $this->url = $this->client->getBaseUrl() . $this->apiUrl . '/' . $uuid;

        return $this->client->put($this->url, [
            'name' => $name,
            'description' => $description
        ]);
    }

    /**
     * @throws \Exception
     */
    public function delete(string $uuid): Response
    {
        $this->url = $this->client->getBaseUrl() . $this->apiUrl . '/' . $uuid;

        return $this->client->delete($this->url);
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
