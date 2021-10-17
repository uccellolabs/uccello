<?php

namespace Uccello\Core\Providers;

use App\Providers\RouteServiceProvider as DefaultRouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
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

            Session::put('workspace', $workspace);

            return $workspace;
        });

        // Bind module
        Route::bind('module', function ($value) {
            $module = $this->retrieveModule($value);
            Session::put('module', $module);
            return $module;
        });
    }

    private function retrieveModule($name)
    {
        $instance = new \App\Modules\UccelloModules;

        $moduleClass = optional($instance->modules)[$name];

        if ($moduleClass) {
            $module = new $moduleClass;
        } else {
            $module = null;
            abort(404);
        }

        return $module;
    }
}
