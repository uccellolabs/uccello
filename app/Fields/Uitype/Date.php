<?php

namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Support\Structure\Field;

class Date
{
    use DefaultUitype;

    public function value(Field $field, $record)
    {
        $datetime = $record->{$field->column};

        if ($datetime) {
            $value = $datetime->format(config('uccello.format.php.date'));
        } else {
            $value = '';
        }

        return $value;
    }
}
