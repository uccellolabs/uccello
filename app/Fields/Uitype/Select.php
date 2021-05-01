<?php

namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Facades\Uccello;
use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Support\Structure\Field;

class Select
{
    use DefaultUitype;

    public function value(Field $field, $record)
    {
        $value = $record->{$field->column};

        if ($value) {
            $value = Uccello::trans($value, $record->module);
        } else {
            $value = '';
        }

        return $value;
    }
}
