<?php

namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Fields\Traits\UccelloUitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;

class Week implements Uitype
{
    use DefaultUitype;
    use UccelloUitype;

    /**
     * Returns field type used by Form builder.
     *
     * @param \Uccello\Core\Models\Field $field
     * @return string
     */
    public function getFormType(Field $field) : string
    {
        return 'text';
    }

    /**
     * Returns options for Form builder.
     *
     * @param mixed $record
     * @param \Uccello\Core\Models\Field $field
     * @param \Uccello\Core\Models\Module $module
     * @return array
     */
    public function getFormOptions($record, Field $field, Module $module) : array
    {
        $options[ 'attr' ] = [ 'class' => 'form-control weekpicker' ];

        return $options;
    }
}