<?php

namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Models\Field;

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
        $value = $record->{$field->name};

        $label = $value ? uctrans('yes', $field->module) : uctrans('no', $field->module);

        return  ucfirst($label);
    }
}