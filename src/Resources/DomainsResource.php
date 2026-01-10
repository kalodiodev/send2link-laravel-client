<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Resources;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Kalodiodev\Send2Link\Contracts\FetchableInterface;
use Kalodiodev\Send2Link\Exceptions\Send2LinkException;
use Kalodiodev\Send2Link\Models\Domain;

class DomainsResource extends AbstractResource implements FetchableInterface
{
    protected string $apiUrl = '/api/v1/domains';
    protected string $resultsKey = 'content';

    /**
     * @throws Send2LinkException
     * @throws ConnectionException
     */
    public function all(): Collection
    {
        return $this->fetchCollection();
    }

    protected function parseItem(mixed $item): Domain
    {
        return new Domain(
            name: (string) $item
        );
    }
}
