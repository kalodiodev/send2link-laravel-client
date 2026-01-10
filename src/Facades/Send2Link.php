<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Facades;

use Illuminate\Support\Facades\Facade;
use Kalodiodev\Send2Link\Send2LinkService;

/**
 * @method static \Kalodiodev\Send2Link\Resources\WorkspacesResource workspaces()
 * @method static \Kalodiodev\Send2Link\Resources\AccountResource account()
 * @method static \Kalodiodev\Send2Link\Resources\ProjectsResource projects(string $workspaceSlug)
 * @method static \Kalodiodev\Send2Link\Resources\ShortLinksResource shortLinks(string $workspaceSlug, string $projectUuid)
 * @method static \Kalodiodev\Send2Link\Resources\DomainsResource domains()
 *
 * @see \Kalodiodev\Send2Link\Send2LinkService
 */
class Send2Link extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'send2link';
    }
}
