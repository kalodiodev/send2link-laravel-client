<?php

namespace Kalodiodev\Send2Link;

use Exception;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Auth\AuthenticationException;
use Kalodiodev\Send2Link\Queries\ProjectsQuery;
use Kalodiodev\Send2Link\Queries\ShortLinksQuery;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Client
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
     * @throws AuthenticationException
     * @throws Exception
     */
    public function get($url): Response
    {
        $response = $this->client()->get($url);

        if ($response->clientError()) {
            $this->throwClientError($response);
        }

        return $response;
    }

    /**
     * Client HTTP put
     *
     * @param $url
     * @param $data
     * @return Response
     * @throws AuthenticationException
     */
    public function patch($url, $data): Response
    {
        $response = $this->client()->patch($url, $data);

        if ($response->clientError()) {
            Log::error($response);
            $this->throwClientError($response);
        }

        return $response;
    }

    /**
     * @param Response $response
     * @throws AuthenticationException|Exception
     */
    protected function throwClientError(Response $response)
    {
        if ($response->status() === ResponseAlias::HTTP_UNAUTHORIZED) {
            throw new AuthenticationException(message: $response->body());
        }

        if ($response->status() === ResponseAlias::HTTP_NOT_FOUND) {
            throw new NotFoundHttpException(message: $response->json('error'), code: $response->status());
        }

        if ($response->status() === ResponseAlias::HTTP_CONFLICT) {
            throw new ConflictHttpException(message: $response->json('error'), code: $response->status());
        }

        throw new Exception(message: $response->json('error'), code: $response->status());
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
     * @throws Exception
     */
    public function delete(string $url): Response
    {
        $response = $this->client()->delete($url);

        if ($response->clientError()) {
            $this->throwClientError($response);
        }

        return $response;
    }

    /**
     * @throws AuthenticationException
     */
    public function post(string $url, array $data): Response
    {
        $response = $this->client()->post($url, $data);

        if ($response->clientError()) {
            Log::error($response);
            $this->throwClientError($response);
        }

        return $response;
    }
}
