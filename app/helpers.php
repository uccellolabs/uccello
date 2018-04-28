<?php

use Sardoj\Uccello\Module;

if (! function_exists('uctrans')) {
    /**
     * Retrieve prefix and translate the given message.
     *
     * @param  string  $key
     * @param  Module|null  $module
     * @param  array   $replace
     * @param  string  $locale
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    function uctrans($key = null, ?Module $module = null, $replace = [], $locale = null)
    {
        if (is_null($key)) {
            return app('translator');
        }

        // If $module is an instance of Module class, add a prefix before the key
        if (!is_null($module) && Module::class == get_class($module))
        {
            // By default prefix is same as the module's name
            $prefix = $module->name;

            // If it is an uccello core module, add uccello:: before
            if (preg_match('/Sardoj\\\Uccello/', $module->entityClass)) {
                $prefix = 'uccello::'.$prefix;
            }

            $key = $prefix.$key;
        }

        return app('translator')->trans($key, $replace, $locale);
    }
}