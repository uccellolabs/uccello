<?php

namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class Choice extends Select implements Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @return string
     */
    public function getFormType() : string
    {
        return 'select';
    }

    /**
     * Returns options for Form builder.
     *
     * @param mixed $record
     * @param \Uccello\Core\Models\Field $field
     * @param \Uccello\Core\Models\Domain $domain
     * @param \Uccello\Core\Models\Module $module
     * @return array
     */
    public function getFormOptions($record, Field $field, Domain $domain, Module $module) : array
    {
        if (!is_object($field->data)) {
            return [ ];
        }

        $options = parent::getFormOptions($record, $field, $domain, $module);

        $choices = [ ];
        if ($field->data->choices) {
            foreach ($field->data->choices as $choice) {
                $choices[ $choice ] = uctrans($choice, $module);
            }
        }

        $options = [
            'choices' => $choices,
            'selected' => $field->data->default ?? null,
            // 'empty_value' => uctrans('field.select_empty_value', $module),
            'attr' => [
                // 'class' => 'form-control show-tick',
                // 'data-live-search' => 'true'
                'multiple' => $field->data->multiple ?? false
            ],
        ];

        return $options;
    }

    /**
     * Returns formatted value to display.
     *
     * @param \Uccello\Core\Models\Field $field
     * @param mixed $record
     * @return string
     */
    public function getFormattedValueToDisplay(Field $field, $record) : string
    {
        return parent::getFormattedValueToDisplay($field, $record);
    }
}