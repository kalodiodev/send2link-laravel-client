<?php

namespace Kalodiodev\Send2Link\Response;

use Kalodiodev\Send2Link\Models\ShortLink;

class ShortLinkResponse
{
    private int $status;
    private ShortLink $shortLink;

    public function __construct(int $status, ShortLink $project)
    {
        $this->status = $status;
        $this->shortLink = $project;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getShortLink(): ShortLink
    {
        return $this->shortLink;
    }
}
