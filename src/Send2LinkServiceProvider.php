<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link;

use Illuminate\Support\ServiceProvider;

class Send2LinkServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/sendtolink.php' => config_path('sendtolink.php'),
            ], 'send2link-config');
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sendtolink.php', 'sendtolink');

        $this->app->singleton(Send2LinkService::class, function ($app) {
            $config = $app->make('config');

            return new Send2LinkService(
                $config->get('sendtolink.server', 'https://send2link.eu'),
                $config->get('sendtolink.authorization_key', ''),
                (int) $config->get('sendtolink.timeout_seconds', 10)
            );
        });

        $this->app->alias(Send2LinkService::class, 'send2link');
    }
}
