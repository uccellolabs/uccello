<?php

namespace Uccello\Core\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Uitype;
use Uccello\Core\Models\Displaytype;
use Uccello\Core\Models\Capability;
use Uccello\Core\Models\Entity;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Filter;

class Uccello
{
    /**
     * Returns true if multi domains are used, false else.
     *
     * @return boolean
     */
    public function useMultiDomains()
    {
        return config('uccello.domains.multi_domains');
    }

    /**
     * Retrieve prefix and translate the given message.
     * If the translation does not exist try to find a default one.
     * If no translation exists display only the key.
     *
     * Priority:
     * 1 - Translation overrided in app
     * 2 - Translation in package
     * 3 - Default translation overrided in app
     * 4 - Default translation in uccello
     * 5 - No translation
     *
     * @param  string  $key
     * @param  Module|null  $module
     * @param  array   $replace
     * @param  string  $locale
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    public function trans($key = null, ?Module $module = null, $replace = [], $locale = null)
    {
        $translator = app('translator');

        if (is_null($key)) {
            return $translator;
        }

        // If $module is an instance of Module class, add a prefix before the key
        if (!is_null($module) && Module::class == get_class($module)) {
            // By default prefix is same as the module's name
            $prefix = $module->name . '.';

            // 1. Get translation in app
            // Compatibilty with new version of Laravel
            if (method_exists($translator, 'get')) {
                $translation = $translator->get($prefix . $key, $replace, $locale);
            } else {
                $translation = $translator->trans($prefix . $key, $replace, $locale);
            }

            if ($translation !== $prefix . $key) {
                return $translation;
            }

            // 2. Get translation in package
            if (!empty($module->package)) {
                // If a package name is defined add it before
                $prefix = $module->package . '::' . $prefix;

                // Compatibilty with new version of Laravel
                if (method_exists($translator, 'get')) {
                    $translation = $translator->get($prefix . $key, $replace, $locale);
                } else {
                    $translation = $translator->trans($prefix . $key, $replace, $locale);
                }

                if ($translation !== $prefix . $key) {
                    return $translation;
                }
            }

            // 3. Try with default translation in app
            if (method_exists($translator, 'get')) {
                $appDefaultTranslation = $translator->get('default.' . $key, $replace, $locale);
            } else {
                $appDefaultTranslation = $translator->trans('default.' . $key, $replace, $locale);
            }

            if ($appDefaultTranslation !== 'default.' . $key) { // If default translation exists then use it
                return $appDefaultTranslation;
            }

            // 4. Try with default translation in uccello
            if (method_exists($translator, 'get')) {
                $uccelloDefaultTranslation = $translator->get('uccello::default.' . $key, $replace, $locale);
            } else {
                $uccelloDefaultTranslation = $translator->trans('uccello::default.' . $key, $replace, $locale);
            }

            if ($uccelloDefaultTranslation !== 'uccello::default.' . $key) { // If default translation exists then use it
                return $uccelloDefaultTranslation;
            }

            // 5. If translation does not exist, display only the key
            return $key;
        }

        // Default behaviour
        if (method_exists($translator, 'get')) {
            return $translator->get($key, $replace, $locale);
        } else {
            return $translator->trans($key, $replace, $locale);
        }
    }

    /**
     * Detects which view it must use and returns the evaluated view contents.
     *
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
        $appModuleView = 'uccello.modules.' . $module->name . '.' . $viewName;

        // Default view overrided in app
        $appDefaultView = 'uccello.modules.default.' . $viewName;

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
     * Makes route automaticaly and add module parameter.
     *
     * @param array|string $name
     * @param Domain|string|null $domain
     * @param Module|string|null $module
     * @param mixed $parameters
     * @param boolean $absolute
     * @return string
     */
    public function route($name, $domain = null, $module = null, $parameters = [], $absolute = true): string
    {
        if (is_a($domain, Domain::class)) {
            $domain = $domain->slug;
        } else {
            $domain = $this->getDomain($domain)->slug ?? null;
        }

        if (is_a($module, Module::class)) {
            $module = $module->name;
        } else {
            $module = $this->getModule($module)->name ?? null;
        }

        // Get route uri to check if domain and module parameters are needed
        $routeUri = Route::getRoutes()->getByName($name)->uri ?? null;

        // Add domain to route if we use multi domains and if the parameter is needed
        if (!is_null($domain) && $this->useMultiDomains() && preg_match('`{domain}`', $routeUri)) {
            $parameters['domain'] = $domain;
        }

        // Add module to route if the parameter is needed
        if (!is_null($module) && preg_match('`{module}`', $routeUri)) {
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
     * Get a domain instance by slug or id
     *
     * @param string|int $slugOrId
     * @return Domain|null
     */
    public function getDomain($slugOrId): ?Domain
    {
        if (is_numeric($slugOrId)) {
            return Domain::find($slugOrId);
        } else {
            return Domain::where('slug', (string)$slugOrId)->first();
        }
    }

    /**
     * Get a module instance by name or id
     *
     * @param string|int $nameOrId
     * @return Module|null
     */
    public function getModule($nameOrId): ?Module
    {
        if (!$nameOrId) {
            return null;
        }

        if (is_numeric($nameOrId)) {
            // Use cache
            $modules = Cache::rememberForever('modules_by_id', function () {
                $modulesGroupedById = collect();
                Module::all()->map(function ($item) use ($modulesGroupedById) {
                    $modulesGroupedById[$item->id] = $item;
                    return $modulesGroupedById;
                });
                return $modulesGroupedById;
            });
            return $modules[(string) $nameOrId] ?? null;
        } else {
            // Use cache
            $modules = Cache::rememberForever('modules_by_name', function () {
                $modulesGroupedByName = collect();
                Module::all()->map(function ($item) use ($modulesGroupedByName) {
                    $modulesGroupedByName[$item->name] = $item;
                    return $modulesGroupedByName;
                });
                return $modulesGroupedByName;
            });
            return $modules[(string) $nameOrId] ?? null;
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
        if (!$nameOrId) {
            return null;
        }

        if (is_numeric($nameOrId)) {
            // Use cache
            $uitypes = Cache::rememberForever('uitypes_by_id', function () {
                $uitypesGroupedById = collect();
                Uitype::all()->map(function ($item) use ($uitypesGroupedById) {
                    $uitypesGroupedById[$item->id] = $item;
                    return $uitypesGroupedById;
                });
                return $uitypesGroupedById;
            });
            return $uitypes[(string) $nameOrId] ?? null;
        } else {
            // Use cache
            $uitypes = Cache::rememberForever('uitypes_by_name', function () {
                $uitypesGroupedByName = collect();
                Uitype::all()->map(function ($item) use ($uitypesGroupedByName) {
                    $uitypesGroupedByName[$item->name] = $item;
                    return $uitypesGroupedByName;
                });
                return $uitypesGroupedByName;
            });
            return $uitypes[(string) $nameOrId] ?? null;
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
        if (!$nameOrId) {
            return null;
        }

        if (is_numeric($nameOrId)) {
            // Use cache
            $displaytypes = Cache::rememberForever('displaytypes_by_id', function () {
                $displaytypesGroupedById = collect();
                Displaytype::all()->map(function ($item) use ($displaytypesGroupedById) {
                    $displaytypesGroupedById[$item->id] = $item;
                    return $displaytypesGroupedById;
                });
                return $displaytypesGroupedById;
            });
            return $displaytypes[(string) $nameOrId] ?? null;
        } else {
            // Use cache
            $displaytypes = Cache::rememberForever('displaytypes_by_name', function () {
                $displaytypesGroupedByName = collect();
                Displaytype::all()->map(function ($item) use ($displaytypesGroupedByName) {
                    $displaytypesGroupedByName[$item->name] = $item;
                    return $displaytypesGroupedByName;
                });
                return $displaytypesGroupedByName;
            });
            return $displaytypes[(string) $nameOrId] ?? null;
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
        if (!$nameOrId) {
            return null;
        }

        if (is_numeric($nameOrId)) {
            // Use cache
            $capabilities = Cache::rememberForever('capabilities_by_id', function () {
                $capabilitiesGroupedById = collect();
                Capability::all()->map(function ($item) use ($capabilitiesGroupedById) {
                    $capabilitiesGroupedById[$item->id] = $item;
                    return $capabilitiesGroupedById;
                });
                return $capabilitiesGroupedById;
            });
            return $capabilities[(string) $nameOrId] ?? null;
        } else {
            // Use cache
            $capabilities = Cache::rememberForever('capabilities_by_name', function () {
                $capabilitiesGroupedByName = collect();
                Capability::all()->map(function ($item) use ($capabilitiesGroupedByName) {
                    $capabilitiesGroupedByName[$item->name] = $item;
                    return $capabilitiesGroupedByName;
                });
                return $capabilitiesGroupedByName;
            });
            return $capabilities[(string) $nameOrId] ?? null;
        }
    }

    /**
     * Returns all domains without parents
     *
     * @return Collection
     */
    public function getRootDomains(): Collection
    {
        return Domain::getRoots()->get();
    }

    /**
     * Get last domain visited by the connected user, or the first one available
     * Priority:
     * 1. Last domain visited
     * 2. Domain where the user was created into
     * 3. First root domain
     *
     * @return Domain|null
     */
    public function getLastOrDefaultDomain(): ?Domain
    {
        $domain = Auth::user()->lastDomain ?? Auth::user()->domain ?? null; // On login page user is not authenticated

        if (!$domain) {
            $domain = $this->getRootDomains()[0];
        }

        return $domain;
    }

    /**
     * Retrieve columns to display in a datatable table
     *
     * @param Module $module
     * @param integer $filterId
     * @param string $type
     * @return array
     */
    public function getDatatableColumns(Module $module, $filterId = null, $type = 'list'): array
    {
        $columns = [];

        // Get default filter
        if ($filterId) {
            $filter = Filter::find($filterId);
        } else {
            $filter = Filter::where('module_id', $module->id)
                ->where('type', $type)
                ->first();

            // If there is not result, try with type = list
            if (empty($filter) && $type !== 'list') {
                $filter = Filter::where('module_id', $module->id)
                    ->where('type', 'list')
                    ->first();
            }
        }

        if (empty($filter)) {
            return [];
        }

        $fieldsAdded = [];

        $seeDescendants = request()->hasSession() && request()->session()->get('descendants') ? true : false;

        // Get filter fields
        foreach ($filter->columns as $fieldName) {
            $field = $module->fields->where('name', $fieldName)->first();

            // If the field does not exist or is not listable, continue
            if (!$field  || !$field->isListable() || in_array($fieldName, $fieldsAdded)) {
                continue;
            }

            $uitype = uitype($field->uitype_id);

            // Add the field as a new column
            $columns[] = [
                'name' => $field->name,
                'db_column' => $field->column,
                'uitype' => $uitype->name,
                'package' => $uitype->package,
                'data' => $field->data,
                'visible' => true
            ];

            $fieldsAdded[] = $fieldName;
        }

        // Get all other fields
        $otherFields = $module->fields->whereNotIn('name', $fieldsAdded);

        foreach ($otherFields as $field) {
            // If the field is not listable, continue
            if (!$field->isListable()) {
                continue;
            }

            $uitype = uitype($field->uitype_id);

            // Add the field as a new column
            $columns[] = [
                'name' => $field->name,
                'db_column' => $field->column,
                'uitype' => $uitype->name,
                'package' => $uitype->package,
                'data' => $field->data,
                'visible' => $field->name === 'domain' && $seeDescendants ? true : false
            ];
        }

        return $columns;
    }

    /**
     * Returns the module's default filter according to the type.
     *
     * @param Module $module
     * @param string $type
     * @return Filter
     */
    public function getDefaultFilter(Module $module, $type = "list")
    {
        $filter = Filter::where('module_id', $module->id)
            ->where('type', $type)
            ->first();

        // If there is not result, try with type = list
        if (empty($filter) && $type !== 'list') {
            $filter = Filter::where('module_id', $module->id)
                ->where('type', 'list')
                ->first();
        }

        return $filter;
    }

    /**
     * Returns a record attribute value.
     * It is able to follow a complex path according to models definition (e.g. 'domain.parent.name')
     *
     * @param Object $record
     * @param string $attribute
     * @param \Uccello\Core\Models\Domain|null $checkPermissionsForDomain
     * @return string|Object|Array|null
     */
    public function getRecordAttribute($record, string $attribute, ?Domain $checkPermissionsForDomain = null)
    {
        $attributeParts = explode('.', $attribute);

        if (count($attributeParts) > 0) {
            $value = $record;

            foreach ($attributeParts as $part) {
                // Check if current user can retrieve data from the module
                if ($checkPermissionsForDomain) {
                    // Get module from class name
                    $module = Module::where('model_class', get_class($value))->first();

                    // Check permissions
                    if (!$module || !Auth::user()->canRetrieve($checkPermissionsForDomain, $module)) {
                        $value = null;
                        break;
                    }
                }

                // Get attribute value if exists
                if (isset($value->{$part})) {
                    $value = $value->{$part};
                } else { // If property does not exist return an empty value
                    $value = null;
                    break;
                }
            }
        } else {
            $value = $record->{$attribute};
        }

        return $value;
    }

    /**
     * Returns a record field value.
     * It is able to follow a complex path according to models definition (e.g. 'domain.parent.name')
     *
     * @param Object $record
     * @param string $fieldName
     * @param \Uccello\Core\Models\Domain|null $checkPermissionsForDomain
     * @return string|Object|Array|null
     */
    public function getRecordFieldValue($record, string $fieldName, ?Domain $checkPermissionsForDomain = null)
    {
        $value = null;

        $fieldNameParts = explode('.', $fieldName);

        if (count($fieldNameParts) > 1) {
            $newRecord = $record;
            $finalFieldExists = false;

            for ($i=0; $i<count($fieldNameParts)-1; $i++) {
                $field = $this->getField($fieldNameParts[$i], $newRecord);

                if ($field->uitype_id === uitype('entity')->id) {
                    $relatedModule = Module::where('name', $field->data->module)->first();

                    if ($checkPermissionsForDomain) {
                        if (!Auth::user()->canRetrieve($checkPermissionsForDomain, $relatedModule)) {
                            break;
                        }
                    }

                    $modelClass = $relatedModule->model_class;
                    $newRecordId = $newRecord->{$field->column};
                    $newRecord = $modelClass::find($newRecordId);

                    if ($i === count($fieldNameParts)-2) {
                        $finalFieldExists = true;
                    }
                }
            }

            if ($finalFieldExists) {
                $value = $this->getFieldFormattedValue($fieldNameParts[count($fieldNameParts)-1], $newRecord);
            }
        } else {
            $value = $this->getFieldFormattedValue($fieldName, $record);
        }

        return $value;
    }

    public function getField($fieldName, $record)
    {
        $field = null;

        $module = $record->module;
        if ($module) {
            $field = $module->fields()->where('name', $fieldName)->first();
        }

        return $field;
    }

    public function getFieldFormattedValue($fieldName, $record)
    {
        $field = $this->getField($fieldName, $record);

        if (empty($field)) {
            return null;
        }

        return uitype($field->uitype_id)->getFormattedValueToDisplay($field, $record);
    }

    /**
     * Retrieves a record by its id or uuid
     *
     * @param int|string $idOrUuid
     * @param string|null $className
     * @return mixed
     */
    public function getRecordByIdOrUuid($idOrUuid, $className = null)
    {
        $record = null;

        if (is_numeric($idOrUuid) && !empty($className)) {
            $record = $className::find($idOrUuid);
        } else {
            $record = $this->getRecordByUuid($idOrUuid);
        }

        return $record;
    }

    /**
     * Retrieves a record by its uuid
     *
     * @param string $uuid
     * @return mixed
     */
    public function getRecordByUuid($uuid)
    {
        $record = null;

        if (!empty($uuid)) {
            $entity = Entity::find($uuid);
            if ($entity) {
                $record = $entity->record;
            }
        }

        return $record;
    }

    /**
     * Checks if the module is related to a Model.
     *
     * @return boolean
     */
    public function isCrudModule(Module $module)
    {
        return !empty($module->model_class) && class_exists($module->model_class);
    }

    /**
     * Checks if the module extends \Gzero\EloquentTree\Model\Tree.
     *
     * @return boolean
     */
    public function isTreeModule(Module $module)
    {
        $modelClass = $module->model_class;

        return (new $modelClass) instanceof \Uccello\EloquentTree\Contracts\Tree;
    }
}
