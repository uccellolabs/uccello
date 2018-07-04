<?php

namespace Uccello\Core\Fields\Traits;

use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;


trait DefaultUitype
{
    /**
     * Returns options for Form builder.
     *
     * @param Field $field
     * @param Module $module
     * @return array
     */
    public function getFormOptions(Field $field, Module $module): array
    {
        return [];
    }

    /**
     * Returns default icon.
     *
     * @return string|null
     */
    public function getDefaultIcon() : ?string
    {
        return null;
    }

    /**
     * Returns value to display.
     *
     * @param Field $field
     * @param mixed $record
     * @return string
     */
    public function getDisplayedValue(Field $field, $record) : string
    {
        return $record->{$field->name} ?? '';
    }
}