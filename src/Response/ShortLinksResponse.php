<?php

namespace Kalodiodev\Send2Link\Response;

use Illuminate\Support\Collection;
use Kalodiodev\Send2Link\Models\ShortLink;

class ShortLinksResponse extends ItemsResponse
{
    /**
     * @return Collection<ShortLink>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }
}
