<?php

namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Models\Field;

class Image extends File implements Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @param \Uccello\Core\Models\Field $field
     * @return string
     */
    public function getFormType(Field $field) : string
    {
        return 'file';
    }

    /**
     * Returns default icon.
     *
     * @param \Uccello\Core\Models\Field $field
     * @return string|null
     */
    public function getDefaultIcon(Field $field) : ?string
    {
        return 'image';
    }
}