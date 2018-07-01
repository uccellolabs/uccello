<?php

namespace Uccello\Core\Models\Uitypes;

use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;

class Choice extends Select implements Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @return string
     */
    public function getFormType(): string
    {
        return 'choice';
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
        if (!is_object($field->data)) {
            return [];
        }

        $options = parent::getFormOptions($field, $module);

        $options['expanded'] = true;
        $options['multiple'] = $field->data->multiple ?? false;

        return $options;
    }

    /**
     * Returns default icon.
     *
     * @return string|null
     */
    public function getDefaultIcon() : ?string
    {
        return null;
    }
}