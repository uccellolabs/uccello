<?php

namespace Uccello\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Uccello\Core\Console\Commands\UccelloInstallCommand;
use Uccello\Core\Console\Commands\UccelloUserCommand;

/**
 * App Service Provider
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
    // For compatibility
    Schema::defaultStringLength(191);

    // Config
    $this->publishes([
        __DIR__ . '/../../config/uccello.php' => config_path('uccello.php'),
    ], 'config');

    // Views
    $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'uccello');
    $this->publishes([
        __DIR__ . '/../../resources/views' => resource_path('views/vendor/uccello')
    ], 'views');

    // Publish assets
    $this->publishes([
        __DIR__ . '/../../public' => public_path('vendor/uccello/uccello'),
        __DIR__ . '/../../public/fonts/vendor' => public_path('fonts/vendor'),
        __DIR__ . '/../../public/images/vendor' => public_path('images/vendor')
    ], 'assets');

    // Translations
    $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'uccello');

    // Migrations
    $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

    // Commands
    if ($this->app->runningInConsole()) {
        $this->commands([
            UccelloInstallCommand::class,
            UccelloUserCommand::class
        ]);
    }
    }

    public function register()
    {
    // Config
    $this->mergeConfigFrom(
        __DIR__ . '/../../config/uccello.php',
        'uccello'
    );

    // Helper
    App::bind('uccello', function () {
        return new \Uccello\Core\Helpers\Uccello;
    });

    // Factories
    $this->registerEloquentFactoriesFrom(__DIR__.'/../../database/factories');
    }

    /**
     * Register factories.
     *
     * @param  string  $path
     * @return void
     */
    protected function registerEloquentFactoriesFrom($path)
    {
        $this->app->make(EloquentFactory::class)->load($path);
    }
}