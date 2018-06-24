<?php

namespace Sardoj\Uccello\Helpers;

use Illuminate\Support\Collection;
use Sardoj\Uccello\Models\Module;
use Sardoj\Uccello\Models\Permission;

class Uccello
{
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
            if (preg_match('/Sardoj\\\Uccello/', $module->entity_class)) {
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
     * Retrieve a module by its name and return its entity class.
     *
     * @param string|null $moduleName
     * @return string|null
     */
    public function getEntityClassByModuleName(?string $moduleName): ?string
    {
        if (is_null($moduleName)) {
            return null;
        }

        $module = Module::where('name', $moduleName)->first();

        return $module->entity_class ?? null;
    }

    /**
     * Detects which view it must use and returns the evaluated view contents.
     * Priority:
     * 1 - Module view overrided in app
     * 2 - Default view overrided in app
     * 3 - Module view ovverrided in uccello
     * 4 - Default view defined in uccello
     * 5 - Fallback view if defined
     *
     * @param Module $module
     * @param string $viewName
     * @param string|null $fallbackView
     * @return string|null
     */
    public function view(Module $module, string $viewName, ?string $fallbackView = null): ?string
    {
        // Module view overrided in app
        $appModuleView = 'modules.' . $module->name . '.' . $viewName;

        // Default view overrided in app
        $appDefaultView = 'modules.default.' . $viewName;

        // Module view ovverrided in uccello
        $uccelloModuleView = 'uccello::modules.' . $module->name . '.' . $viewName;

        // Default view defined in uccello
        $uccelloDefaultView = 'uccello::modules.default.' . $viewName;

        $viewToInclude = null;
        if (view()->exists($appModuleView)) {
            $viewToInclude = $appModuleView;
        } elseif (view()->exists($appDefaultView)) {
            $viewToInclude = $appDefaultView;
        } elseif (view()->exists($uccelloModuleView)) {
            $viewToInclude = $uccelloModuleView;
        } elseif (view()->exists($uccelloDefaultView)) {
            $viewToInclude = $uccelloDefaultView;
        } elseif (!is_null($fallbackView)) {
            $viewToInclude = $fallbackView;
        }

        return $viewToInclude;
    }

    /**
     * Returns the list of capabilities.
     * Important: A capability name must begin by CAPABILITY_
     *
     * @return \Illuminate\Support\Collection
     *
     * @see Sardoj\Uccello\Models\Permission
     */
    public function getCapabilities(): Collection
    {
        $class = new \ReflectionClass(Permission::class);

        $capabilities = new Collection();
        foreach ($class->getConstants() as $constant => $value) {
            if (preg_match('`^CAPABILITY_`', $constant)) {
                $capabilities[] = $value;
            }
        }

        return $capabilities;
    }

    /**
     * Get a module instance by name or id
     *
     * @param string|int $name
     * @return Module|null
     */
    public function getModuleInstance($nameOrId): ?Module
    {
        if (is_numeric($nameOrId)) {
            return Module::find($nameOrId);
        } else {
            return Module::where('name', (string) $nameOrId)->first();
        }
    }
}