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
use Uccello\Core\Models\Filter;

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

            // If a package name is defined add it before
            if (!empty($module->package)) {
                $prefix = $module->package . '::'. $prefix;
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
     * 5 - Module view ovverrided in uccello
     * 6 - Default view defined in uccello
     * 7 - Fallback view if defined
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

        // Module view ovverrided in uccello
        $uccelloModuleView = 'uccello::modules.' . $module->name . '.' . $viewName;

        // Default view defined in uccello
        $uccelloDefaultView = 'uccello::modules.default.' . $viewName;

        $viewToInclude = null;
        if (view()->exists($appModuleView)) {
            $viewToInclude = $appModuleView;
        } elseif (view()->exists($appDefaultView)) {
            $viewToInclude = $appDefaultView;
        } elseif (view()->exists($packageModuleView)) {
            $viewToInclude = $packageModuleView;
        } elseif (view()->exists($packageDefaultView)) {
            $viewToInclude = $packageDefaultView;
        }  elseif (view()->exists($uccelloModuleView)) {
            $viewToInclude = $uccelloModuleView;
        } elseif (view()->exists($uccelloDefaultView)) {
            $viewToInclude = $uccelloDefaultView;
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

    /**
     * Retrieve columns to display in a datatable table
     *
     * @param Module $module
     * @return array
     */
    public function getDatatableColumns(Module $module): array
    {
        $columns = [];

        // Get default filter
        $filter = Filter::where('module_id', $module->id)
            ->where('type', 'list')
            ->first();

        // Get all fields
        $fields = $module->fields;

        foreach ($fields as $field) {
            // If the field is not listable, continue
            if (!$field->isListable()){
                continue;
            }

            // Add the field as a new column
            $columns[] = [
                'name' => $field->name,
                'db_column' => $field->column,
                'uitype' => $field->uitype->name,
                'package' => $field->uitype->package,
                'data' => $field->data,
                'visible' => in_array($field->name, $filter->columns)
            ];
        }

        return $columns;
    }

    /**
     * Returns a record attribute value.
     * It is able to follow a complex path according to models definition (e.g. 'domain.parent.name')
     *
     * @param Object $record
     * @param string $attribute
     * @return string|Object|Array|null
     */
    public function getRecordAttribute($record, string $attribute) {

        $attributeParts = explode('.', $attribute);

        if (count($attributeParts) > 0) {
            $value = $record;

            foreach ($attributeParts as $part) {
                // Get attribute value if exists
                if (isset($value->{$part})) {
                    $value = $value->{$part};
                }
                // If property does not exist return an empty value
                else {
                    $value = null;
                    break;
                }
            }
        } else {
            $value = $record->{$attribute};
        }

        return $value;
    }
}