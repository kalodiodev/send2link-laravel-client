<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/** @internal */
class Send2LinkClient
{
    private string $server;
    private string $authorizationKey;
    private int $timeout;

    public function __construct(
        string $server,
        string $authorizationKey,
        int $timeout = 10
    ) {
        $this->server = rtrim($server, '/');
        $this->authorizationKey = $authorizationKey;
        $this->timeout = $timeout;
    }

    protected function client(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->authorizationKey,
            'Accept' => 'application/json',
        ])->timeout($this->timeout);
    }

    /**
     * Get results
     *
     * @param string $url
     * @return Response
     * @throws RequestException|ConnectionException
     */
    public function get(string $url): Response
    {
        $response = $this->client()->get($url);

        if ($response->clientError()) {
            $response->throw();
        }

        return $response;
    }

    /**
     * Client HTTP patch
     *
     * @param string $url
     * @param $data
     * @return Response
     * @throws RequestException|ConnectionException
     */
    public function patch(string $url, $data): Response
    {
        $response = $this->client()->patch($url, $data);

        if ($response->clientError()) {
            Log::error($response->body());
            $response->throw();
        }

        return $response;
    }

    /**
     * Get Base url
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->server;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @throws RequestException|ConnectionException
     */
    public function delete(string $url): Response
    {
        $response = $this->client()->delete($url);

        if ($response->clientError()) {
            $response->throw();
        }

        return $response;
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function post(string $url, array $data): Response
    {
        $response = $this->client()->post($url, $data);

        if ($response->clientError()) {
            $response->throw();
        }

        return $response;
    }
}
