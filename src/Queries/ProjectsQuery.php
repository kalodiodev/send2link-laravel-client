<?php

namespace Kalodiodev\Send2Link\Queries;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Client\Response;

class ProjectsQuery extends QueryBuilder
{
    protected string $apiUrl = 'api/v1/projects';
    protected string $resultsKey = 'projects';

    public function get(): Response
    {
        return $this->client->get($this->url);
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
        return null;
    }
}
