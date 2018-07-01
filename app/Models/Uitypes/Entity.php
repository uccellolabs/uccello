<?php

namespace Uccello\Core\Models\Uitypes;

use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;

class Entity implements Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @return string
     */
    public function getFormType(): string
    {
        return 'entity';
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

        $options = [];

        if ($field->data->module) {
            $options = [
                'class' => ucmodule($field->data->module)->model_class ?? null,
                'property' => $field->data->field ?? 'name',
                'empty_value' => uctrans('select_empty_value', $module)
            ];
        }

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