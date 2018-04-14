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
    // Get namespace
    $nameSpace = $this->app->getNamespace();

    // Config
    $this->publishes([
      __DIR__.'/../../config/uccello.php' => config_path('uccello.php'),
    ], 'config');

    // Routes
    $this->app->router->group(['namespace' => $nameSpace . 'Http\Controllers'], function()
    {
      require __DIR__.'/../Http/routes.php';
    });

    // Views
    $this->loadViewsFrom(__DIR__.'/../../resources/views', 'uccello');
    $this->publishes([
        __DIR__.'/../../resources/views' => resource_path('views/vendor/sardoj')
    ], 'views');

    // Commands
    if ($this->app->runningInConsole()) {
      $this->commands([
          UccelloMakeCommand::class,
      ]);
    }
  }

  public function register() {}
}