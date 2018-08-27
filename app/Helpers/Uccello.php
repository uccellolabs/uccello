<?php

namespace Uccello\Core\Helpers;

use Illuminate\Support\Collection;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Permission;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Uitype;
use Uccello\Core\Models\Displaytype;
use Uccello\Core\Models\Capability;
use Illuminate\Support\Facades\Auth;

class Uccello
{
    /**
     * Returns true if multi domains are used, false else.
     *
     * @return void
     */
    public function useMultiDomains()
    {
        return env('UCCELLO_MULTI_DOMAINS', true) !== false;
    }

    /**
     * Retrieve prefix and translate the given message. If the default translation does not exist try to find a fallback one.
     *
     * @param  string  $key
     * @param  Module|null  $module
     * @param  array   $replace
     * @param  string  $locale
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    public function trans($key = null, ?Module $module = null, $replace = [], $locale = null)
    {
        if (is_null($key)) {
            return app('translator');
        }

        // If $module is an instance of Module class, add a prefix before the key
        if (!is_null($module) && Module::class == get_class($module))
        {
            // By default prefix is same as the module's name
            $prefix = $module->name.'.';

            // If it is an uccello core module, add uccello:: before
            if (preg_match('/Uccello\\\Core/', $module->model_class)) {
                $prefix = 'uccello::'.$prefix;
            }

            // Get translation
            $translation = app('translator')->trans($prefix.$key, $replace, $locale);

            // If translation does not exist, try with fallback one
            if ($translation === $prefix.$key) {

                // Get fallback translation
                $fallbackTranslation = app('translator')->trans('uccello::default.'.$key, $replace, $locale);

                // If fallback translation exists then use it
                if ($fallbackTranslation !== 'uccello::default.'.$key) {
                    $translation = $fallbackTranslation;
                }
            }

            return $translation;
        }

        // Default behaviour
        return app('translator')->trans($key, $replace, $locale);
    }

    /**
     * Detects which view it must use and returns the evaluated view contents.
     * Priority:
     * 1 - Module view overrided in app
     * 2 - Default view overrided in app
     * 3 - Module view ovverrided in package
     * 4 - Default view defined in package
     * 5 - Fallback view if defined
     *
     * @param string $package
     * @param Module $module
     * @param string $viewName
     * @param string|null $fallbackView
     * @return string|null
     */
    public function view(string $package, Module $module, string $viewName, ?string $fallbackView = null): ?string
    {
        // Module view overrided in app
        $appModuleView = 'modules.' . $module->name . '.' . $viewName;

        // Default view overrided in app
        $appDefaultView = 'modules.default.' . $viewName;

        // Module view ovverrided in package
        $packageModuleView = $package . '::modules.' . $module->name . '.' . $viewName;

        // Default view defined in package
        $packageDefaultView = $package . '::modules.default.' . $viewName;

        $viewToInclude = null;
        if (view()->exists($appModuleView)) {
            $viewToInclude = $appModuleView;
        } elseif (view()->exists($appDefaultView)) {
            $viewToInclude = $appDefaultView;
        } elseif (view()->exists($packageModuleView)) {
            $viewToInclude = $packageModuleView;
        } elseif (view()->exists($packageDefaultView)) {
            $viewToInclude = $packageDefaultView;
        } elseif (!is_null($fallbackView)) {
            $viewToInclude = $fallbackView;
        }

        return $viewToInclude;
    }

    /**
     * Makes route automaticaly and add module parameter.
     *
     * @param array|string $name
     * @param Domain|string|null $domain
     * @param Module|string|null $module
     * @param mixed $parameters
     * @param boolean $absolute
     * @return string
     */
    public function route($name, $domain = null, $module = null, $parameters = [], $absolute = true) : string
    {
        if (is_a($domain, Domain::class)) {
            $domain = $domain->slug;
        }

        if (is_a($module, Module::class)) {
            $module = $module->name;
        }

        // Add domain to route if we use multi domains
        if (!is_null($domain) && uccello()->useMultiDomains()) {
            $parameters['domain'] = $domain;
        }

        if (!is_null($module)) {
            $parameters['module'] = $module;
        }

        return route($name, $parameters, $absolute);
    }

    /**
     * Returns the list of capabilities.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * @see Uccello\Core\Models\Permission
     */
    public function getCapabilities(): Collection
    {
        return Capability::all();
    }

    /**
     * Get a module instance by name or id
     *
     * @param string|int $nameOrId
     * @return Module|null
     */
    public function getModule($nameOrId): ?Module
    {
        if (is_numeric($nameOrId)) {
            return Module::find($nameOrId);
        } else {
            return Module::where('name', (string) $nameOrId)->first();
        }
    }

    /**
     * Get an Uitype instance by name or id
     *
     * @param string|int $nameOrId
     * @return Uitype|null
     */
    public function getUitype($nameOrId): ?Uitype
    {
        if (is_numeric($nameOrId)) {
            return Uitype::find($nameOrId);
        } else {
            return Uitype::where('name', (string) $nameOrId)->first();
        }
    }

    /**
     * Get a display type instance by name or id
     *
     * @param string|int $nameOrId
     * @return Uitype|null
     */
    public function getDisplaytype($nameOrId): ?Displaytype
    {
        if (is_numeric($nameOrId)) {
            return Displaytype::find($nameOrId);
        } else {
            return Displaytype::where('name', (string) $nameOrId)->first();
        }
    }

    /**
     * Get a capability instance by name or id
     *
     * @param string|int $nameOrId
     * @return Uitype|null
     */
    public function getCapability($nameOrId): ?Capability
    {
        if (is_numeric($nameOrId)) {
            return Capability::find($nameOrId);
        } else {
            return Capability::where('name', (string) $nameOrId)->first();
        }
    }

    /**
     * Returns all domains without parents
     *
     * @return Collection
     */
    public function getRootDomains(): Collection
    {
        return Domain::whereNull('parent_id')->get();
    }

    /**
     * Get last domain visited by the connected user, or the first one available
     *
     * @return Domain|null
     */
    public function getLastOrDefaultDomain(): ?Domain
    {
        $domain = Auth::user()->lastDomain ?? null; // On login page user is not authenticated

        if (!$domain) {
            $domain = $this->getRootDomains()[0];
        }

        return $domain;
    }
}