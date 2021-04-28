<?php

namespace Uccello\Core\Providers;

use App\Providers\RouteServiceProvider as DefaultRouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Uccello\Core\Models\Workspace;
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

        // Bind workspace
        Route::bind('workspace', function ($value) {
            if (preg_match('`^[0-9]+$`', $value)) { // By id
                $workspace = Workspace::findOrFail($value);
            } else { // By slug
                $workspace = Workspace::where('slug', $value)->first() ?? abort(404);
            }
            return $workspace;
        });

        // Bind module
        Route::bind('module', function ($value) {
            if (preg_match('`^[0-9]+$`', $value)) { // By id
                $module = Module::findOrFail($value);
            } else { // By name
                $module = Module::where('name', $value)->first() ?? abort(404);
            }
            return $module;
        });
    }
}
