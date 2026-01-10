<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Resources;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Kalodiodev\Send2Link\Exceptions\Send2LinkException;
use Kalodiodev\Send2Link\Models\Account;
use Kalodiodev\Send2Link\Response\ItemResponse;

class AccountResource extends AbstractResource
{
    protected string $apiUrl = '/api/v1/account';

    /**
     * Get account information
     *
     * @return ItemResponse<Account>
     * @throws Send2LinkException|ConnectionException
     */
    public function get(): ItemResponse
    {
        try {
            $response = $this->getRequest();

            if ($response->successful()) {
                $item = $this->parseItem($response->json());
                return new ItemResponse($response->status(), $item);
            }

            throw $this->createException($response);
        } catch (RequestException $e) {
            throw $this->createException($e->response);
        }
    }

    protected function parseItem(mixed $item): Account
    {
        return new Account(
            email: $item['email'] ?? '',
            firstName: $item['firstName'] ?? '',
            lastName: $item['lastName'] ?? '',
            createdAt: $item['createdAt'] ?? '',
            projectsCount: (int) ($item['projectsCount'] ?? 0),
            shortLinksCount: (int) ($item['shortLinksCount'] ?? 0)
        );
    }
}

