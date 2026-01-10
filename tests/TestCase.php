<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Tests;

use Kalodiodev\Send2Link\Send2LinkServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            Send2LinkServiceProvider::class,
        ];
    }
}
