<?php

namespace Sardoj\Uccello\Providers;
 
use Illuminate\Support\ServiceProvider;
use Sardoj\Uccello\Console\Commands\UccelloMakeCommand;

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
    // Config
    $this->publishes([
      __DIR__.'/../../config/uccello.php' => config_path('uccello.php'),
    ], 'config');

    // Views
    $this->loadViewsFrom(__DIR__.'/../../resources/views', 'uccello');
    $this->publishes([
        __DIR__.'/../../resources/views' => resource_path('views/vendor/sardoj')
    ], 'views');

    // Translations
    $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'uccello');

    // Migrations
    $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

    // Commands
    if ($this->app->runningInConsole()) {
      $this->commands([
          UccelloMakeCommand::class,
      ]);
    }
  }

  public function register()
  {
    
  }
}