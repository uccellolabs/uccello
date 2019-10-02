<?php

use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

if (!function_exists('uccello')) {
    /**
     * Return an instance of Uccello\Core\Helpers\Uccello
     *
     * @return void
     */
    function uccello()
    {
        return app('uccello');
    }
}

if (!function_exists('uctrans')) {
    /**
     * Retrieve prefix and translate the given message. If the default translation does not exist try to find a fallback one.
     *
     * @param  string  $key
     * @param  Module|null  $module
     * @param  array   $replace
     * @param  string  $locale
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     *
     * @see Uccello\Core\Helpers\Uccello
     */
    function uctrans($key = null, ? Module $module = null, $replace = [ ], $locale = null)
    {
        return app('uccello')->trans($key, $module, $replace, $locale);
    }
}

if (!function_exists('ucdomain')) {
    /**
     * Get a module instance by name or id
     *
     * @param string|int $slugOrId
     * @return Domain|null
     */
    function ucdomain($slugOrId): ?Domain
    {
        return app('uccello')->getDomain($slugOrId);
    }
}

if (!function_exists('ucmodule')) {
    /**
     * Get a module instance by name or id
     *
     * @param string|int $name
     * @return Module|null
     */
    function ucmodule($nameOrId): ?Module
    {
        return app('uccello')->getModule($nameOrId);
    }
}

if (!function_exists('ucroute')) {
    /**
     * Makes route automaticaly and add domain and module parameter.
     *
     * @param array|string $name
     * @param Domain|string|null $domain
     * @param Module|string|null $module
     * @param mixed $parameters
     * @param boolean $absolute
     * @return string
     */
    function ucroute($name, $domain = null, $module = null, $parameters = [ ], $absolute = true) : string
    {
        return app('uccello')->route($name, $domain, $module, $parameters, $absolute);
    }
}

if (!function_exists('uclog')) {
    /**
     * Use logging
     *
     * @param mixed $message
     * @return void
     */
    function uclog($message, $type='info')
    {
        Log::$type($message);
    }
}

if (!function_exists('uitype')) {
    /**
     * Get an uitype instance by name or id
     *
     * @param string|int $nameOrId
     * @return Uitype|null
     */
    function uitype($nameOrId)
    {
        return app('uccello')->getUitype($nameOrId);
    }
}

if (!function_exists('displaytype')) {
    /**
     * Get a display type instance by name or id
     *
     * @param string|int $nameOrId
     * @return Displaytype|null
     */
    function displaytype($nameOrId)
    {
        return app('uccello')->getDisplaytype($nameOrId);
    }
}

if (!function_exists('capability')) {
    /**
     * Get a capability instance by name or id
     *
     * @param string|int $nameOrId
     * @return Capabillity|null
     */
    function capability($nameOrId)
    {
        return app('uccello')->getCapability($nameOrId);
    }
}

if (!function_exists('ucasset')) {
    /**
     * Get complete path to asset
     *
     * @param string $path
     * @param string $package
     * @return void
     */
    function ucasset($path, $package = 'uccello/uccello')
    {
        return asset("vendor/$package/$path");
    }
}

if (!function_exists('ucattribute')) {
    /**
     * Returns a record attribute value.
     * It is able to follow a complex path according to models definition (e.g. 'domain.parent.name')
     *
     * @param Object $record
     * @param string $attribute
     * @return string|Object|Array|null
     */
    function ucattribute($record, $attribute)
    {
        return app('uccello')->getRecordAttribute($record, $attribute);
    }
}

if (!function_exists('ucnotify')) {
    /**
     * Uses flash session to display a notification.
     *
     * @param string $message
     * @param string $type
     * @return void
     */
    function ucnotify($message, $type = 'info') {
        session()->flash('notification-'.$type, $message);
    }
}

if (!function_exists('ucrecord')) {
    /**
     * Uses flash session to display a notification.
     *
     * @param int $message
     * @param string $message
     * @return Entity
     */
    function ucrecord($idOrUid, $className = null)
    {
        if (is_numeric($idOrUid))
        {
            $className::find($idOrUid);
        }
        else
        {
            return uccello()->getRecordByUid($idOrUid);
        }
    }
}