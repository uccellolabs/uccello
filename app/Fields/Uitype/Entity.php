<?php

namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Support\Structure\Field;

class Entity
{
    use DefaultUitype;

    public function value(Field $field, $record)
    {
        return $record->{$field->column} ?? '';
    }
}
