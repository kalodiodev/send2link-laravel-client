<?php

namespace Kalodiodev\Send2Link\Queries;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Kalodiodev\Send2Link\Client;

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

    protected function addParameter($key, $value): void
    {
        $this->url .= $key . "=" . $value . "&";
    }

    /**
     * Set Page and Page Size
     */
    public function page($page, $pageSize = 25): self
    {
        $this->addParameter('pageNumber', $page);
        $this->addParameter('pageSize', $pageSize);

        return $this;
    }

    /**
     * Get Results
     *
     * @return Response
     * @throws AuthenticationException
     */
    protected function performGet(): Response
    {
        $this->url = rtrim($this->url, '&');

        return $this->client->get($this->url);
    }

    /**
     * Update
     *
     * @param array $data
     * @return Response
     * @throws AuthenticationException
     */
    public function put(array $data): Response
    {
        $this->url = rtrim($this->url, '&');

        return $this->client->put($this->url, $data);
    }

    /**
     * Results collection
     *
     * @param callable|null $parser
     * @return Collection|mixed
     * @throws AuthenticationException
     */
    public function collection(Callable $parser = null): mixed
    {
        $results = $this->json();

        if (! empty($this->resultsKey)) {
            return $this->buildCollection($results, $parser);
        }

        return $this->parseItem($results);
    }

    /**
     * Results json
     *
     * @return array|mixed
     * @throws AuthenticationException
     */
    public function json(): mixed
    {
        return $this->performGet()->json();
    }

    /**
     * Build results collection
     *
     * @param array $results
     * @param callable|null $itemCallback
     * @return Collection
     */
    protected function buildCollection(array $results, callable $itemCallback = null): Collection
    {
        $collection = new Collection();

        foreach ($results[$this->resultsKey] as $data) {
            if ($itemCallback) {
                $itemCallback($data);
            }

            $collection->add($this->parseItem($data));
        }

        return $collection;
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
