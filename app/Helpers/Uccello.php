<?php

namespace Uccello\Core\Helpers;

use Illuminate\Support\Facades\Cache;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Uitype;

class Uccello
{
    /**
     * Returns true if multi workspaces option is activated, false else.
     *
     * @return boolean
     */
    public function useMultiWorkspaces()
    {
        return config('uccello.workspace.multi_workspaces');
    }

    /**
     * Get an Uitype instance by name or id
     *
     * @param string|int $nameOrId
     * @return Uitype|null
     */
    public function uitype($nameOrId): ?Uitype
    {
        if (!$nameOrId) {
            return null;
        }

        if (is_numeric($nameOrId)) {
            // Use cache
            $uitypes = Cache::remember('uitypes_by_id', now()->addMinutes(10), function () {
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
            $uitypes = Cache::remember('uitypes_by_name', now()->addMinutes(10), function () {
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
            $translation = $translator->get($prefix . $key, $replace, $locale);

            if ($translation !== $prefix . $key) {
                return $translation;
            }

            // 2. Get translation in package
            if (!empty($module->package)) {
                // If a package name is defined add it before
                $prefix = $module->package . '::' . $prefix;

                // Compatibilty with new version of Laravel
                $translation = $translator->get($prefix . $key, $replace, $locale);

                if ($translation !== $prefix . $key) {
                    return $translation;
                }
            }

            // 3. Try with default translation in app
            $appDefaultTranslation = $translator->get('default.' . $key, $replace, $locale);

            if ($appDefaultTranslation !== 'default.' . $key) { // If default translation exists then use it
                return $appDefaultTranslation;
            }

            // 4. Try with default translation in uccello
            $uccelloDefaultTranslation = $translator->get('uccello::default.' . $key, $replace, $locale);

            // If default translation exists then use it
            if ($uccelloDefaultTranslation !== 'uccello::default.' . $key) {
                return $uccelloDefaultTranslation;
            }

            // 5. If translation does not exist, display only the key
            return $key;
        }

        // Default behaviour
        return $translator->get($key, $replace, $locale);
    }
}
