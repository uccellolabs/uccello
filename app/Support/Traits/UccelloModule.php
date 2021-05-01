<?php

namespace Uccello\Core\Support\Traits;

use Illuminate\Support\Facades\Cache;
use Uccello\Core\Models\Module;

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

    /**
     * Retrieve module from record.
     * It use record model to retrieve the related module.
     *
     * @return \Uccello\Core\Models\Module|<null></null>
     */
    public function getModuleAttribute()
    {
        $modelClass = get_class($this);

        $module = Cache::remember("module_by_model_$modelClass", now()->addMinutes(10), function () {
            return Module::where('data->model', get_class($this))->first();
        });

        return $module;
    }
}
