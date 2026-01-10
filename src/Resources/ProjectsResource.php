<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Resources;

use Illuminate\Http\Client\ConnectionException;
use Kalodiodev\Send2Link\Exceptions\Send2LinkException;
use Kalodiodev\Send2Link\Models\Project;
use Kalodiodev\Send2Link\Response\ItemResponse;
use Kalodiodev\Send2Link\Response\PageResponse;
use Kalodiodev\Send2Link\Send2LinkClient;
use Kalodiodev\Send2Link\Contracts\ReadOnlyResourceInterface;
use Kalodiodev\Send2Link\Contracts\PaginatableInterface;
use Kalodiodev\Send2Link\Contracts\DeletableInterface;

class ProjectsResource extends AbstractResource implements ReadOnlyResourceInterface, PaginatableInterface, DeletableInterface
{
    protected string $apiUrl = '';
    protected string $resultsKey = 'content';
    protected string $workspaceSlug;

    public function __construct(Send2LinkClient $client, string $workspaceSlug)
    {
        parent::__construct($client);
        $this->workspaceSlug = $workspaceSlug;

        $this->apiUrl = '/api/v1/workspaces/' . rawurlencode($this->workspaceSlug) . '/projects';
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
     * Create Project
     *
     * @throws Send2LinkException|ConnectionException
     */
    public function create(string $name, ?string $description = null): ItemResponse
    {
        return $this->postRequest([
            'name' => $name,
            'description' => $description
        ]);
    }

    /**
     * Update Project
     *
     * @throws Send2LinkException|ConnectionException
     */
    public function update(string $uuid, string $name, ?string $description = null): void
    {
        $this->patchRequest($uuid, [
            'name' => $name,
            'description' => $description
        ]);
    }

    protected function parseItem(mixed $item): Project
    {
        return new Project(
            $item['uuid'],
            $item['name'],
            $item['description'] ?? null,
            $item['createdAt'],
            $item['updatedAt']
        );
    }
}
