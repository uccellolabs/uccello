<?php

namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Fields\Traits\UccelloUitype;
use Uccello\Core\Models\Field;

class Text implements Uitype
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
}