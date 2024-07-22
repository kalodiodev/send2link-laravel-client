<?php

namespace Kalodiodev\Send2Link;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class Send2LinkServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/sendtolink.php' => config_path('sendtolink.php'),
        ]);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sendtolink.php', 'sendtolink');
        $this->app->bind(Send2LinkClient::class, function (Application $app) {
            return new Send2LinkClient(
                $app->make('config')->get('sendtolink.server'),
                $app->make('config')->get('sendtolink.authorization_key'),
            );
        });
    }
}
