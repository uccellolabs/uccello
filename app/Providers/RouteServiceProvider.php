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
            if (preg_match('`[0-9]+`', $value)) { // By id
                $domain = Domain::findOrFail($value);
            } else { // By slug
                $domain = Domain::where('slug', $value)->first() ?? abort(404);
            }
            return $domain;
        });

        // Bind module
        Route::bind('module', function ($value) {
            if (preg_match('`[0-9]+`', $value)) { // By id
                $module = Module::findOrFail($value);
            } else { // By name
                $module = Module::where('name', $value)->first() ?? abort(404);
            }
            return $module;
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