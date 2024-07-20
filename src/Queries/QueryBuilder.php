<?php

namespace Kalodiodev\Send2Link\Queries;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Kalodiodev\Send2Link\Client;
use Kalodiodev\Send2Link\Response\ItemResponse;
use Kalodiodev\Send2Link\Response\PageResponse;

abstract class QueryBuilder
{
    protected Client $client;
    protected string $url;
    protected string $apiUrl = '';

    /**
     * If result is a list, provide the array key otherwise leave empty
     *
     * @var string
     */
    protected string $resultsKey = '';

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->url = $client->getBaseUrl() . $this->apiUrl . "?";
    }

    /**
     * Set Page and Page Size
     *
     * @param int $page the page number
     * @param int $pageSize the page size
     */
    public function page(int $page, int $pageSize = 25): self
    {
        $this->addParameter('pageNumber', $page);
        $this->addParameter('pageSize', $pageSize);

        return $this;
    }

    /**
     * Get page items
     *
     * @throws RequestException
     */
    public function getAll(): PageResponse
    {
        $response = $this->getRequest();

        if ($response->successful()) {
            $projects = $this->parseItems($response->json($this->resultsKey));

            return new PageResponse(
                $response->status(),
                $response->json('page'),
                $response->json('size'),
                $response->json('totalPages'),
                $response->json('first'),
                $response->json('last'),
                $projects
            );
        }

        return new PageResponse($response->status(),0,0,0, true, true, collect());
    }

    /**
     * Get Item by UUID
     *
     * @throws RequestException
     */
    public function getByUuid(string $uuid): ItemResponse
    {
        $this->url = $this->client->getBaseUrl() . $this->apiUrl . '/' . $uuid;

        $response = $this->client->get($this->url);

        $item = $this->parseItem($response->json());

        return new ItemResponse($response->status(), $item);
    }

    /**
     * Delete item
     *
     * @throws RequestException
     */
    public function delete(string $uuid): void
    {
        $this->url = $this->client->getBaseUrl() . $this->apiUrl . '/' . $uuid;

        $this->client->delete($this->url);
    }

    /**
     * Add parameter to URL
     *
     * @param $key
     * @param $value
     * @return void
     */
    protected function addParameter($key, $value): void
    {
        $this->url .= $key . "=" . $value . "&";
    }

    /**
     * Make POST Request
     *
     * @throws RequestException
     */
    protected function postRequest($data): ItemResponse
    {
        $this->url = $this->client->getBaseUrl() . $this->apiUrl;

        $response = $this->client->post($this->url, $data);

        $project = $this->parseItem($response->json());

        return new ItemResponse($response->status(), $project);
    }

    /**
     * Make GET request
     *
     * @return Response
     * @throws RequestException
     */
    protected function getRequest(): Response
    {
        $this->url = rtrim($this->url, '&');

        return $this->client->get($this->url);
    }

    /**
     * Make Patch Request
     *
     * @throws RequestException
     */
    protected function patchRequest($uuid, $data): void
    {
        $this->url = $this->client->getBaseUrl() . $this->apiUrl . '/' . $uuid;

        $this->client->patch($this->url, $data);
    }

    /**
     * Get query url
     *
     * @return string
     */
    public function queryUrl(): string
    {
        return rtrim($this->url, '&');
    }

    /**
     * Parse Items to Collection
     *
     * @param array $responseData
     * @return Collection
     */
    protected function parseItems(array $responseData): Collection
    {
        $items = [];

        foreach ($responseData as $responseItem) {
            $items[] = $this->parseItem($responseItem);
        }

        return collect($items);
    }

    /**
     * Parse results item to object
     *
     * @param array $item
     * @return mixed
     */
    abstract protected function parseItem(array $item): mixed;
}
