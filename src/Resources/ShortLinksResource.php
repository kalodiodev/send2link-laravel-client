<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Resources;

use Illuminate\Http\Client\ConnectionException;
use Kalodiodev\Send2Link\Exceptions\Send2LinkException;
use Kalodiodev\Send2Link\Models\ShortLink;
use Kalodiodev\Send2Link\Response\ItemResponse;
use Kalodiodev\Send2Link\Response\PageResponse;
use Kalodiodev\Send2Link\Send2LinkClient;
use Kalodiodev\Send2Link\Contracts\ReadOnlyResourceInterface;
use Kalodiodev\Send2Link\Contracts\PaginatableInterface;
use Kalodiodev\Send2Link\Contracts\DeletableInterface;

class ShortLinksResource extends AbstractResource implements ReadOnlyResourceInterface, PaginatableInterface, DeletableInterface
{
    protected string $apiUrl = '';
    protected string $resultsKey = 'content';
    protected string $projectUuid;
    protected string $workspaceSlug;

    public function __construct(Send2LinkClient $client, string $workspaceSlug, string $projectUuid)
    {
        parent::__construct($client);
        $this->workspaceSlug = $workspaceSlug;
        $this->projectUuid = $projectUuid;

        $this->apiUrl = '/api/v1/workspaces/' . rawurlencode($this->workspaceSlug) . '/projects/' . rawurlencode($this->projectUuid) . '/shortlinks';
    }

    /**
     * @throws Send2LinkException
     * @throws ConnectionException
     */
    public function all(): PageResponse
    {
        return $this->fetchAll();
    }

    /**
     * @throws Send2LinkException
     * @throws ConnectionException
     */
    public function find(string $uuid): ItemResponse
    {
        return $this->fetchItem($uuid);
    }

    public function paginate(int $page, int $size = 25): static
    {
        return $this->setPagination($page, $size);
    }

    /**
     * @throws Send2LinkException
     * @throws ConnectionException
     */
    public function delete(string $uuid): void
    {
        $this->deleteResource($uuid);
    }

    /**
     * Create shortLink
     *
     * @throws Send2LinkException
     * @throws ConnectionException
     */
    public function create(string $destination, bool $enabled, ?string $domain = null, ?string $expireInDays = null): ItemResponse
    {
        $data = [
            'destination' => $destination,
            'enabled' => $enabled
        ];

        if ($domain !== null) {
            $data['domain'] = $domain;
        }

        if ($expireInDays !== null) {
            $data['expireInDays'] = $expireInDays;
        }

        return $this->postRequest($data);
    }

    /**
     * Update ShortLink
     *
     * @throws Send2LinkException
     * @throws ConnectionException
     */
    public function update(string $shortLinkUuid, string $destination, bool $enabled): void
    {
        $this->patchRequest($shortLinkUuid, [
            'destination' => $destination,
            'enabled' => $enabled
        ]);
    }

    protected function parseItem(mixed $item): ShortLink
    {
        return new ShortLink(
            $item['uuid'],
            $item['link'],
            $item['destination'],
            $item['enabled'],
            $item['expiresAt'] ?? null,
            $item['createdAt'],
            $item['updatedAt'],
        );
    }
}
