<?php

namespace Uccello\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Uccello\Core\Console\Commands\InstallCommand;
use Uccello\Core\Console\Commands\UserCommand;
use Uccello\Core\Console\Commands\PublishCommand;

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
        $this->registerMigrations();
        $this->registerTranslations();
        $this->registerPublishing();

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'uccello');
    }

    /**
     * Register the package's migrations.
     *
     * @return void
     */
    private function registerMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }
    }

    /**
     * Register the package's translations.
     *
     * @return void
     */
    private function registerTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'uccello');
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            // Publish assets
            $this->publishes([
                __DIR__ . '/../../public' => public_path('vendor/uccello/uccello'),
                __DIR__ . '/../../public/images/vendor' => public_path('images/vendor')
            ], 'uccello-assets');

            // Config
            $this->publishes([
                __DIR__ . '/../../config/uccello.php' => config_path('uccello.php'),
            ], 'uccello-config');

            // Views
            $this->publishes([
                __DIR__ . '/../../resources/views' => resource_path('views/vendor/uccello')
            ], 'uccello-views');
        }
    }

    /**
     * Register factories.
     *
     * @param string $path
     * @return void
     */
    protected function registerEloquentFactoriesFrom($path)
    {
        $this->app->make(EloquentFactory::class)->load($path);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
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

        // Commands
        $this->commands([
            InstallCommand::class,
            UserCommand::class,
            PublishCommand::class,
        ]);
    }
}
