<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Resources;

use Illuminate\Http\Client\ConnectionException;
use Kalodiodev\Send2Link\Exceptions\Send2LinkException;
use Kalodiodev\Send2Link\Models\Workspace;
use Illuminate\Support\Collection;
use Kalodiodev\Send2Link\Contracts\FetchableInterface;

class WorkspacesResource extends AbstractResource implements FetchableInterface
{
    protected string $apiUrl = '/api/v1/workspaces';
    protected string $resultsKey = 'content';

    /**
     * @return Collection<Workspace>
     * @throws Send2LinkException
     * @throws ConnectionException
     */
    public function all(): Collection
    {
        return $this->fetchCollection();
    }

    protected function parseItem(mixed $item): Workspace
    {
        return new Workspace(
            name: $item['name'] ?? '',
            slug: $item['slug'] ?? '',
            workspaceRole: $item['workspaceRole'] ?? null,
        );
    }
}
