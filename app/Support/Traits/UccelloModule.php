<?php

namespace Uccello\Core\Support\Traits;

trait UccelloModule
{
    /**
     * Retrieves module model class if defined.
     *
     * @return string
     */
    public function getModelClassAttribute()
    {
        return $this->data->model ?? null;
    }
}
