<?php

use Sardoj\Uccello\Models\Module;

if (!function_exists('uccello')) {
    /**
     * Return an instance of Sardoj\Uccello\Helpers\Uccello
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
     * @see Sardoj\Uccello\Helpers\Uccello
     */
    function uctrans($key = null, ? Module $module = null, $replace = [], $locale = null)
    {
        return app('uccello')->trans($key, $module, $replace, $locale);
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
        return app('uccello')->getModuleInstance($nameOrId);
    }
}

if (!function_exists('uclog')) {
    /**
     * Use Debugbar to log data.
     *
     * @param mixed $data
     * @return void
     */
    function uclog($data)
    {
        \Debugbar::info($data);
    }
}