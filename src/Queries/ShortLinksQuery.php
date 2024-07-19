<?php

namespace Kalodiodev\Send2Link\Queries;

use Illuminate\Auth\AuthenticationException;
use Kalodiodev\Send2Link\Client;
use Kalodiodev\Send2Link\Models\ShortLink;
use Kalodiodev\Send2Link\Response\ItemResponse;
use Kalodiodev\Send2Link\Response\PageResponse;


class ShortLinksQuery extends QueryBuilder
{
    protected string $apiUrl = '/api/v1/projects';
    protected string $resultsKey = 'content';

    private string $projectUuid;

    /**
     * @param Client $client
     * @param string $projectUuid the project's UUID
     */
    public function __construct(Client $client, string $projectUuid)
    {
        parent::__construct($client);

        $this->projectUuid = $projectUuid;
        $this->apiUrl = $this->apiUrl . '/' . $this->projectUuid . '/shortlinks';
        $this->url = $client->getBaseUrl() . $this->apiUrl . "?";
    }

    /**
     * Get all ShortLinks of project
     *
     * @return PageResponse<ShortLink>
     * @throws AuthenticationException
     */
    public function getAll(): PageResponse
    {
        return parent::getAll();
    }

    /**
     * Create shortLink
     *
     * @return ItemResponse<ShortLink>
     * @throws AuthenticationException
     */
    public function create(string $destination, bool $enabled): ItemResponse
    {
        return $this->postRequest([
            'destination' => $destination,
            'enabled' => $enabled
        ]);
    }

    /**
     * Update ShortLink
     *
     * @throws AuthenticationException
     */
    public function update(string $shortLinkUuid, string $destination, bool $enabled): void
    {
        $this->patchRequest($shortLinkUuid, [
            'destination' => $destination,
            'enabled' => $enabled
        ]);
    }

    protected function parseItem(array $item): ShortLink
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
