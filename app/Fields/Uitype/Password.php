<?php

namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Support\Structure\Field;

class Password
{
    use DefaultUitype;

    /**
     * Return formatted value
     *
     * @param \Uccello\Core\Support\Structure\Field $field
     * @param mixed $record
     *
     * @return string
     */
    public function value(Field $field, $record)
    {
        return $record->{$field->column} ? '********' : '';
    }

    /**
     * Return raw value
     *
     * @param \Uccello\Core\Support\Structure\Field $field
     * @param mixed $record
     *
     * @return string
     */
    public function rawValue(Field $field, $record)
    {
        return $record->{$field->column} ? '********' : null;
    }
}
