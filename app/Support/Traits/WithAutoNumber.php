<?php

namespace Uccello\Core\Support\Traits;

use Uccello\Core\Models\Entity;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Filter;
use Illuminate\Support\Facades\Cache;
use Uccello\Core\Support\Scopes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Uccello\Core\Models\Domain;

trait WithAutoNumber
{
    /**
     * The "booting" method of the trait.
     *
     * @return void
     */
    protected static function bootWithAutoNumber()
    {
        // Create uuid after save
        static::creating(function ($model) {
            $module = Module::where('model_class', get_class($model))->first();
            $autoNumberFields = $module->fields()->where('uitype_id', uitype('auto_number')->id)->get();

            foreach ($autoNumberFields as $field) {
                $prefix = $field->data->prefix ?? '';
                $suffix = $field->data->suffix ?? '';
                $strlen_min = $field->data->strlen_min ?? 1;

                $increment = $field->data->increment ?? 1;
                $previousValue = $field->data->current_value ?? ($field->data->start_value-1) ?? 0;
                $currentValue = $previousValue + $increment;

                $value = sprintf("%0".$strlen_min."d", $currentValue);

                $model->{$field->column} = "$prefix$value$suffix";

                // Update current value into field data
                $fieldData = $field->data;
                $fieldData->current_value = $currentValue;
                $field->data = $fieldData;
                $field->save();
            }
        });
    }
}
