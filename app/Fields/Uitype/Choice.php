<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

        // json_decode selected values for an edition
        $selectedValues = null;
        if ($record->{$field->column} ?? false) {
            $selectedValues = json_decode($record->{$field->column});
        }

        $options = [
            'choices' => $choices,
            'selected' => $selectedValues ?? $field->data->default ?? null,
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
     * Return options for Module Designer
     *
     * @param object $bundle
     *
     * @return array
     */
    public function getFieldOptions($bundle) : array
    {
        return [
            [
                'key' => 'choices',
                'label' => trans('uccello::uitype.option.choice.choices'),
                'required' => true,
                'type' => 'array',
            ],
            [
                'key' => 'multiple',
                'label' => trans('uccello::uitype.option.choice.multiple'),
                'type' => 'boolean',
            ],
        ];
    }

    /**
     * Returns formatted value to display.
     * If multiple is true, concat all selected values.
     *
     * @param \Uccello\Core\Models\Field $field
     * @param mixed $record
     * @return string
     */
    public function getFormattedValueToDisplay(Field $field, $record) : string
    {
        if (isset($field->data->multiple) && $field->data->multiple === true && !empty($record->{$field->column})) {
            $values = [];

            $fieldValues = json_decode($record->{$field->column});
            if (is_array($fieldValues)) {
                foreach ($fieldValues as $value) {
                    if (empty($value)) {
                        continue;
                    }

                    $values[] =  uctrans($value, $field->module);
                }
            }

            $value = implode(', ', $values);
        } else {
            $value =  parent::getFormattedValueToDisplay($field, $record);
        }

        return $value;
    }

    /**
     * Returns formatted value to save.
     * If multiple is true, json_encode the $value (is an array).
     *
     * @param \Illuminate\Http\Request $request
     * @param \Uccello\Core\Models\Field $field
     * @param mixed|null $value
     * @param mixed|null $record
     * @param \Uccello\Core\Models\Domain|null $domain
     * @param \Uccello\Core\Models\Module|null $module
     * @return string|null
     */
    public function getFormattedValueToSave(Request $request, Field $field, $value, $record = null, ?Domain $domain = null, ?Module $module = null) : ?string
    {
        return isset($field->data->multiple) && $field->data->multiple === true ? json_encode($value) : $value;
    }
}
