<?php

namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class Boolean extends Checkbox implements Uitype
{
    /**
     * Returns value to display.
     * Display Yes or No instead of 1 or 0.
     *
     * @param Field $field
     * @param mixed $record
     * @return string
     */
    public function getDisplayedValue(Field $field, $record) : string
    {
        $value = $record->{$field->column};

        $label = $value ? uctrans('yes', $field->module) : uctrans('no', $field->module);

        return  ucfirst($label);
    }

    /**
     * Returns sanitized value for saving.
     * If value is null, returns 0 (false)
     *
     * @param Field $field
     * @param mixed|null $value
     * @param mixed|null $record
     * @param Domain|null $domain
     * @param Module|null $module
     * @return string|null
     */
    public function getSanitizedValueForSaving(Field $field, $value, $record=null, ?Domain $domain=null, ?Module $module=null) : ?string
    {
        return $value ?? 0;
    }
}