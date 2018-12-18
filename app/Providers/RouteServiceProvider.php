<?php

namespace Uccello\Core\Providers;

use App\Providers\RouteServiceProvider as DefaultRouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

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
      // API
      Route::prefix('api')
          ->middleware('api')
          ->namespace('Uccello\Core\Http\Controllers') // We prefer to do this instead of modifying $this->namespace, else LoginController is not find
          ->group(__DIR__.'/../../routes/api.php');

      // Web
      Route::middleware('web', 'auth')
            ->namespace('Uccello\Core\Http\Controllers') // We prefer to do this instead of modifying $this->namespace, else LoginController is not find
            ->group(__DIR__.'/../../routes/web.php');
    }
}