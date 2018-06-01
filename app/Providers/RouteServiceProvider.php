<?php

namespace Sardoj\Uccello\Providers;
 
use App\Providers\RouteServiceProvider as DefaultRouteServiceProvider;
use Sardoj\Uccello\Domain;
use Sardoj\Uccello\Module;
use Illuminate\Support\Facades\Route;

/**
 * Route Service Provider
 */
class RouteServiceProvider extends DefaultRouteServiceProvider
{
  /**
   * @inheritDoc
   */
  public function boot()
  {
    parent::boot();

    // Bind domain
    Route::bind('domain', function ($value) {
      return Domain::where('slug', $value)->first() ?? abort(404);
    });

    // Bind module
    Route::bind('module', function ($value) {
      return Module::where('name', $value)->first() ?? abort(404);
    });
  }

  /**
   * @inheritDoc
   */
  public function map()
  {
    parent::map();

    $this->mapUccelloRoutes();
  }

    /**
     * Define "uccello" routes for the application.
     *
     * @return void
     */
    protected function mapUccelloRoutes()
    {
        Route::middleware('web', 'auth')
             ->namespace('Sardoj\Uccello\Http\Controllers')
             ->group(__DIR__.'/../Http/routes.php');
    }
}