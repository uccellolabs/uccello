<?php

namespace Uccello\Core\Facades;

use Illuminate\Support\Facades\Facade;

class Uccello extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'uccello';
    }
}