<?php

use Sardoj\Uccello\Module;

if (! function_exists('uctrans')) {
    /**
     * Retrieve prefix and translate the given message. If the default translation does not exist try to find a fallback one.
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
}