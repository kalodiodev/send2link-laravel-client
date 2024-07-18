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
use Kalodiodev\Send2Link\Response\ProjectResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Client
{
    private string $server;
    private string $authorizationKey;

    const PROJECTS_URL = '/api/v1/projects';

    public function __construct()
    {
        $this->authorizationKey = config('send2link.server');
        $this->server = config('send2link.authorization_key');
    }

    /**
     * @throws AuthenticationException
     */
    public function createProject(string $name, string $description): ProjectResponse
    {
        $response = $this->client()->post($this->getBaseUrl() . self::PROJECTS_URL, [
            'name' => $name,
            'description' => $description,
        ]);

        if ($response->clientError()) {
            $this->throwClientError($response);
        }

        $project = new Models\Project(
            $response->json()['uuid'],
            $response->json()['name'],
            $response->json()['description'],
            $response->json()['createdAt'],
            $response->json()['updatedAt']
        );

        return new ProjectResponse($response->status(), $project);
    }

    public function getProjects(): ProjectsQuery
    {
        return new ProjectsQuery($this);
    }

    public function getProject(string $projectUuid)
    {

    }

    public function deleteProject(string $projectUuid)
    {

    }

    public function createShortLink(string $projectUuid, string $destination, boolean $enabled)
    {

    }

    public function deleteShortLink(string $projectUuid, string $shortLinkUuid)
    {

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
    public function put($url, $data): Response
    {
        $response = $this->client()->put($url, $data);

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
            throw new AuthenticationException($response->json()['message']);
        }

        if ($response->status() === ResponseAlias::HTTP_NOT_FOUND) {
            throw new NotFoundHttpException($response->json()['message']);
        }

        throw new Exception($response->json()['message']);
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
        $response = $this->client()->put($url, $data);

        if ($response->clientError()) {
            Log::error($response);
            $this->throwClientError($response);
        }

        return $response;
    }
}
