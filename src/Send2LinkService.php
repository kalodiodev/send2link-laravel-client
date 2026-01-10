<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link;

use Kalodiodev\Send2Link\Resources\AccountResource;
use Kalodiodev\Send2Link\Resources\DomainsResource;
use Kalodiodev\Send2Link\Resources\ProjectsResource;
use Kalodiodev\Send2Link\Resources\ShortLinksResource;
use Kalodiodev\Send2Link\Resources\WorkspacesResource;

class Send2LinkService
{
    private Send2LinkClient $client;

    public function __construct(
        string $server,
        string $authorizationKey,
        int $timeout = 10
    ) {
        $this->client = new Send2LinkClient($server, $authorizationKey, $timeout);
    }

    public function workspaces(): WorkspacesResource
    {
        return new WorkspacesResource($this->client);
    }

    public function account(): AccountResource
    {
        return new AccountResource($this->client);
    }

    public function projects(string $workspaceSlug): ProjectsResource
    {
        return new ProjectsResource($this->client, $workspaceSlug);
    }

    public function shortLinks(string $workspaceSlug, string $projectUuid): ShortLinksResource
    {
        return new ShortLinksResource($this->client, $workspaceSlug, $projectUuid);
    }

    public function domains(): DomainsResource
    {
        return new DomainsResource($this->client);
    }
}
