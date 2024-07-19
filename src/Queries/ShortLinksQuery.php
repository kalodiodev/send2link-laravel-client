<?php

namespace Kalodiodev\Send2Link\Queries;

use Illuminate\Auth\AuthenticationException;
use Kalodiodev\Send2Link\Client;
use Kalodiodev\Send2Link\Models\ShortLink;
use Kalodiodev\Send2Link\Response\ShortLinkResponse;
use Kalodiodev\Send2Link\Response\ShortLinksResponse;

class ShortLinksQuery extends QueryBuilder
{
    protected string $apiUrl = '/api/v1/projects';
    protected string $resultsKey = 'content';

    private string $projectUuid;

    public function __construct(Client $client, string $projectUuid)
    {
        parent::__construct($client);

        $this->projectUuid = $projectUuid;
        $this->apiUrl = $this->apiUrl . '/' . $this->projectUuid . '/shortlinks';
        $this->url = $client->getBaseUrl() . $this->apiUrl . "?";
    }

    /**
     * @throws AuthenticationException
     */
    public function getAll(): ShortLinksResponse
    {
        $response = $this->performGet();

        if ($response->successful()) {
            $shortLinks = $this->parseItems($response->json($this->resultsKey));

            return new ShortLinksResponse(
                $response->status(),
                $response->json('page'),
                $response->json('size'),
                $response->json('totalPages'),
                $response->json('first'),
                $response->json('last'),
                $shortLinks
            );
        }

        return new ShortLinksResponse($response->status(),0,0,0, true, true, collect());
    }

    /**
     * @throws AuthenticationException
     */
    public function getByUuid(string $shortLinkUuid): ShortLinkResponse
    {
        $this->url = $this->client->getBaseUrl() . $this->apiUrl . '/' . $shortLinkUuid;

        $response = $this->client->get($this->url);

        $shortLink = $this->parseItem($response->json());

        return new ShortLinkResponse($response->status(), $shortLink);
    }

    /**
     * @throws AuthenticationException
     */
    public function create(string $destination, bool $enabled): ShortLinkResponse
    {
        $this->url = $this->client->getBaseUrl() . $this->apiUrl;

        $response = $this->client->post($this->url, [
            'destination' => $destination,
            'enabled' => $enabled
        ]);

        $shortLink = $this->parseItem($response->json());

        return new ShortLinkResponse($response->status(), $shortLink);
    }

    /**
     * @throws AuthenticationException
     */
    public function update(string $shortLinkUuid, string $destination, bool $enabled): void
    {
        $this->url = $this->client->getBaseUrl() . $this->apiUrl . '/' . $shortLinkUuid;

        $this->client->patch($this->url, [
            'destination' => $destination,
            'enabled' => $enabled
        ]);
    }

    /**
     * @throws \Exception
     */
    public function delete(string $shortLinkUuid): void
    {
        $this->url = $this->client->getBaseUrl() . $this->apiUrl . '/' . $shortLinkUuid;

        $this->client->delete($this->url);
    }

    protected function parseItem(array $item): mixed
    {
        return new ShortLink(
            $item['uuid'],
            $item['link'],
            $item['destination'],
            $item['enabled'],
            $item['createdAt'],
            $item['updatedAt']
        );
    }
}
