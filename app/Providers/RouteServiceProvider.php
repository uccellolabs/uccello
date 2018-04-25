<?php

namespace Sardoj\Uccello\Providers;
 
use Illuminate\Support\ServiceProvider;
use Sardoj\Uccello\Console\Commands\UccelloMakeCommand;
use Illuminate\Support\Facades\Cache;
use App\Providers\RouteServiceProvider as DefaultRouteServiceProvider;
use Sardoj\Uccello\Domain;
use Sardoj\Uccello\Module;

/**
 * Route Service Provider
 */
class RouteServiceProvider extends DefaultRouteServiceProvider
{

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
}