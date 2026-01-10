<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Resources;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Kalodiodev\Send2Link\Send2LinkClient;
use Kalodiodev\Send2Link\Response\ItemResponse;
use Kalodiodev\Send2Link\Response\PageResponse;
use Kalodiodev\Send2Link\Exceptions\AccessDeniedException;
use Kalodiodev\Send2Link\Exceptions\NotFoundException;
use Kalodiodev\Send2Link\Exceptions\UnauthorizedException;
use Kalodiodev\Send2Link\Exceptions\ValidationException;
use Kalodiodev\Send2Link\Exceptions\Send2LinkException;
use Kalodiodev\Send2Link\Contracts\ResourceInterface;

abstract class AbstractResource implements ResourceInterface
{
    protected Send2LinkClient $client;
    protected string $apiUrl = '';
    protected string $resultsKey = '';
    protected array $parameters = [];

    public function __construct(Send2LinkClient $client)
    {
        $this->client = $client;
    }

    /**
     * Make POST Request
     *
     * @throws Send2LinkException
     * @throws ConnectionException
     */
    protected function postRequest(array $data): ItemResponse
    {
        try {
            $url = $this->resolveUrl();
            $response = $this->client->post($url, $data);

            if ($response->successful()) {
                $item = $this->parseItem($response->json());
                return new ItemResponse($response->status(), $item);
            }

            throw $this->createException($response);
        } catch (RequestException $e) {
            throw $this->createException($e->response);
        }
    }

    /**
     * Make GET request
     *
     * @throws RequestException
     * @throws ConnectionException
     */
    protected function getRequest(): Response
    {
        $query = !empty($this->parameters)
            ? '?' . http_build_query($this->parameters, '', '&', PHP_QUERY_RFC3986)
            : '';

        $url = $this->resolveUrl() . $query;

        return $this->client->get($url);
    }

    /**
     * Make Patch Request
     *
     * @throws Send2LinkException
     * @throws ConnectionException
     */
    protected function patchRequest(string $uuid, array $data): void
    {
        try {
            $url = $this->resolveUrl('/' . rawurlencode($uuid));
            $response = $this->client->patch($url, $data);

            if (!$response->successful()) {
                throw $this->createException($response);
            }
        } catch (RequestException $e) {
            throw $this->createException($e->response);
        }
    }

    /**
     * Resolve the full URL with optional appended path
     */
    protected function resolveUrl(string $appendedPath = ''): string
    {
        return rtrim($this->client->getBaseUrl(), '/') . $this->apiUrl . $appendedPath;
    }

    /**
     * @param Response $response
     * @return Send2LinkException
     */
    protected function createException(Response $response): Send2LinkException
    {
        $data = $response->json();
        $message = $data['message'] ?? $response->reason();

        return match ($response->status()) {
            401 => new UnauthorizedException($message, $response->status()),
            403 => new AccessDeniedException($message, $response->status()),
            404 => new NotFoundException($message, $response->status()),
            400, 422 => new ValidationException($message, $data['errors'] ?? [], $response->status()),
            default => new Send2LinkException($message, $response->status()),
        };
    }

    public function queryUrl(): string
    {
        $query = !empty($this->parameters)
            ? '?' . http_build_query($this->parameters, '', '&', PHP_QUERY_RFC3986)
            : '';

        return $this->resolveUrl() . $query;
    }

    /**
     * Fetch a paginated collection of items
     *
     * @throws Send2LinkException
     * @throws ConnectionException
     */
    protected function fetchAll(): PageResponse
    {
        try {
            $response = $this->getRequest();

            if ($response->successful()) {
                $data = $response->json();
                $items = $this->parseItems($data[$this->resultsKey] ?? $data);

                return new PageResponse(
                    $response->status(),
                    $data['page'] ?? 0,
                    $data['size'] ?? $items->count(),
                    $data['totalPages'] ?? 1,
                    $data['totalElements'] ?? $items->count(),
                    $data['first'] ?? true,
                    $data['last'] ?? true,
                    $items
                );
            }

            throw $this->createException($response);
        } catch (RequestException $e) {
            throw $this->createException($e->response);
        }
    }

    /**
     * Fetch a non-paginated collection of items
     *
     * @throws Send2LinkException
     * @throws ConnectionException
     */
    protected function fetchCollection(): Collection
    {
        try {
            $response = $this->getRequest();

            if ($response->successful()) {
                $data = $response->json();
                return $this->parseItems($data[$this->resultsKey] ?? $data);
            }

            throw $this->createException($response);
        } catch (RequestException $e) {
            throw $this->createException($e->response);
        }
    }

    /**
     * Fetch a single item by its UUID
     *
     * @throws Send2LinkException
     * @throws ConnectionException
     */
    protected function fetchItem(string $uuid): ItemResponse
    {
        try {
            $url = $this->resolveUrl('/' . rawurlencode($uuid));
            $response = $this->client->get($url);

            if ($response->successful()) {
                $item = $this->parseItem($response->json());
                return new ItemResponse($response->status(), $item);
            }

            throw $this->createException($response);
        } catch (RequestException $e) {
            throw $this->createException($e->response);
        }
    }

    /**
     * Delete a resource
     *
     * @throws Send2LinkException
     * @throws ConnectionException
     */
    protected function deleteResource(string $uuid): void
    {
        try {
            $url = $this->resolveUrl('/' . rawurlencode($uuid));
            $response = $this->client->delete($url);

            if (!$response->successful()) {
                throw $this->createException($response);
            }
        } catch (RequestException $e) {
            throw $this->createException($e->response);
        }
    }

    /**
     * Set pagination parameters
     */
    protected function setPagination(int $page, int $size): static
    {
        $this->parameters['pageNumber'] = $page;
        $this->parameters['pageSize'] = $size;

        return $this;
    }

    protected function parseItems(array $responseData): Collection
    {
        return collect($responseData)->map(fn($item) => $this->parseItem($item));
    }

    abstract protected function parseItem(mixed $item): mixed;
}
