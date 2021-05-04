<?php

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
     * Retrieve prefix and translate the given message.
     * If the default translation does not exist try to find a fallback one.
     *
     * @param  string  $key
     * @param  Module|null  $module
     * @param  array   $replace
     * @param  string  $locale
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     *
     * @see Uccello\Core\Helpers\Uccello
     */
    function uctrans($key = null, ?Module $module = null, $replace = [ ], $locale = null)
    {
        return app('uccello')->trans($key, $module, $replace, $locale);
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

if (!function_exists('uitype')) {
    /**
     * Get an uitype instance by name or id
     *
     * @param string|int $nameOrId
     * @return Uitype|null
     */
    function uitype($nameOrId)
    {
        return app('uccello')->uitype($nameOrId);
    }
}
