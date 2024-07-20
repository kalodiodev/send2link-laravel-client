<?php

namespace Kalodiodev\Send2Link;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Kalodiodev\Send2Link\Queries\ProjectsQuery;
use Kalodiodev\Send2Link\Queries\ShortLinksQuery;

class Send2LinkClient
{
    private string $server;
    private string $authorizationKey;

    public function __construct()
    {
        $this->server = config('send2link.server');
        $this->authorizationKey = config('send2link.authorization_key');
    }

    public function projects(): ProjectsQuery
    {
        return new ProjectsQuery($this);
    }

    public function shortLinks(string $projectUuid): ShortLinksQuery
    {
        return new ShortLinksQuery($this, $projectUuid);
    }

    protected function client(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->authorizationKey
        ]);
    }

    /**
     * Get results
     *
     * @param $url
     * @return Response
     * @throws RequestException
     */
    public function get($url): Response
    {
        $response = $this->client()->get($url);

        if ($response->clientError()) {
            $response->throw();
        }

        return $response;
    }

    /**
     * Client HTTP put
     *
     * @param $url
     * @param $data
     * @return Response
     * @throws RequestException
     */
    public function patch($url, $data): Response
    {
        $response = $this->client()->patch($url, $data);

        if ($response->clientError()) {
            Log::error($response);
            $response->throw();
        }

        return $response;
    }

    /**
     * Get Base url
     *
     * @return Repository|Application|mixed
     */
    public function getBaseUrl(): mixed
    {
        return $this->server;
    }

    /**
     * @throws RequestException
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
