<?php

namespace Uccello\Core\Models\Uitypes;

use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;

class Select implements Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @return string
     */
    public function getFormType(): string
    {
        return 'select';
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

        $choices = [];
        if ($field->data->choices) {
            foreach ($field->data->choices as $choice) {
                $choices[$choice] = uctrans($choice, $module);
            }
        }

        $options = [
            'choices' => $choices,
            'selected' => $field->data->default ?? null,
            'empty_value' => uctrans('select_empty_value', $module)
        ];

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