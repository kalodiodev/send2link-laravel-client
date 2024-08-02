<?php

namespace Kalodiodev\Send2Link\Queries;

use Illuminate\Http\Client\RequestException;
use Kalodiodev\Send2Link\Response\PageResponse;

class DomainsQuery extends QueryBuilder
{
    protected string $apiUrl = '/api/v1/domains';
    protected string $resultsKey = 'content';

    /**
     * Get all Projects
     *
     * @return PageResponse<string>
     * @throws RequestException
     */
    public function getAll(): PageResponse
    {
        $response = $this->getRequest();

        if ($response->successful()) {
            $domains = $this->parseItems($response->json($this->resultsKey));

            return new PageResponse(
                $response->status(),
                1,
                sizeof($domains),
                1,
                true,
                true,
                $domains
            );
        }

        return new PageResponse($response->status(),0,0,0, true, true, collect());
    }

    protected function parseItem($item): mixed
    {
        return $item;
    }
}
