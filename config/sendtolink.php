<?php

return [
    'server' => env('SEND2LINK_SERVER', 'https://send2link.eu'),
    'authorization_key' => env("SEND2LINK_AUTH_KEY", ''),
    'timeout_seconds' => env('SEND2LINK_TIMEOUT', 10),
];
