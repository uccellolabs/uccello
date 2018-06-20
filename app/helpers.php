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