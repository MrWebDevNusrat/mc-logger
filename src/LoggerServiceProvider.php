<?php

namespace MC\Logger;

use Illuminate\Support\ServiceProvider;

class LoggerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('MC\Logger\LoggerController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->publishes([
            __DIR__.'/database/migrations' => database_path('migrations'),
        ], 'migrations');
        include __DIR__ . "/routes.php";
        $this->app['router']->aliasMiddleware('my.middleware', \MC\Logger\Http\Middleware\LogMiddleware::class);
    }
}
