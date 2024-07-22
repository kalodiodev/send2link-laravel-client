<?php

namespace Kalodiodev\Send2Link;

use Illuminate\Support\ServiceProvider;

class Send2LinkServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/send2link.php' => config_path('send2link.php'),
        ]);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/send2link.php', 'send2link');
        $this->app->bind('send2link', function () {
            return new Send2LinkClient(
                $this->app->make('config')->get('send2link.server', ''),
                $this->app->make('config')->get('send2link.authorization_key', '')
            );
        });
    }
}
