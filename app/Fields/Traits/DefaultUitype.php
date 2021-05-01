<?php

namespace Uccello\Core\Fields\Traits;

use Uccello\Core\Support\Structure\Field;

trait DefaultUitype
{
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
        return $record->{$field->column} ?? '';
    }

    /**
     * Return raw value
     *
     * @param \Uccello\Core\Support\Structure\Field $field
     * @param mixed $record
     *
     * @return mixed|null
     */
    public function rawValue(Field $field, $record)
    {
        return $record->{$field->column} ?? null;
    }
}
