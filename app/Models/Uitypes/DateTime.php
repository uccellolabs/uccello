<?php

namespace Uccello\Core\Models\Uitypes;

use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;

class DateTime implements Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @return string
     */
    public function getFormType(): string
    {
        return 'datetime-local';
    }

    /**
     * Returns options for Form builder.
     *
     * @param Field $field
     * @param Module $module
     * @return array
     */
    public function getFormOptions(Field $field, Module $module): array
    {
        return [];
    }

    /**
     * Returns default icon.
     *
     * @return string|null
     */
    public function getDefaultIcon() : ?string
    {
        return 'date_range';
    }
}