<?php

namespace Uccello\Core\Fields\Uitype;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Fields\Traits\UccelloUitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class Select implements Uitype
{
    use DefaultUitype;
    use UccelloUitype;

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

        $choices = [ ];
        if ($field->data->choices) {
            foreach ($field->data->choices as $choice) {
                $choices[ $choice ] = uctrans($choice, $module);
            }
        }

        $options = [
            'choices' => $choices,
            'selected' => $field->data->default ?? null,
            'empty_value' => uctrans('field.select_empty_value', $module),
            'attr' => [
                // 'class' => 'form-control show-tick',
                // 'data-live-search' => 'true'
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
                'label' => trans('uccello::uitype.option.select.choices'),
                'required' => true,
                'type' => 'array',
            ],
        ];
    }

    /**
     * Return formatted data column and eventualy all related translations.
     *
     * @param object $bundle
     *
     * @return array
     */
    public function getFormattedFieldDataAndTranslationFromOptions($bundle) : array
    {
        $data = (object) $bundle->field->data;
        $data->choices = [];

        $translations = [];

        if (!empty($bundle->field->data['choices'])) {
            foreach ($bundle->field->data['choices'] as $choice) {
                if (is_string($choice)) { // String
                    $data->choices[] = $choice;
                } elseif (is_array($choice)) { // Array (value => label)
                    // Choice
                    if (!empty($choice['value'])) {
                        $data->choices[] = $bundle->field->name . '.' . $choice['value']; // fieldName.value

                        // Translation
                        if (!empty($choice['label'])) {
                            $translations[$choice['value']] = $choice['label'];
                        }
                    }
                }
            }
        }

        return [
            "data" => $data,
            "translation" => [
                $bundle->field->name => $translations
            ],
        ];
    }

    /**
     * Returns formatted value to display.
     * Translate the value.
     *
     * @param \Uccello\Core\Models\Field $field
     * @param mixed $record
     * @return string
     */
    public function getFormattedValueToDisplay(Field $field, $record) : string
    {
        $value = $record->{$field->column} ? uctrans($record->{$field->column}, $field->module) : '';

        return  $value;
    }

    /**
     * Returns updated query after adding a new search condition.
     *
     * @param \Illuminate\Database\Eloquent\Builder query
     * @param \Uccello\Core\Models\Field $field
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function addConditionToSearchQuery(Builder $query, Field $field, $value) : Builder
    {
        $query->where(function ($query) use ($field, $value) {
            foreach ((array) $value as $_value) {
                $formattedValue = $this->getFormattedValueToSearch($_value);
                $query = $query->orWhere($field->column, 'like', $formattedValue);
            }
        });

        return $query;
    }

    /**
     * Ask the user some specific options relative to a field
     *
     * @param \StdClass $module
     * @param \StdClass $field
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function askFieldOptions(\StdClass &$module, \StdClass &$field, InputInterface $input, OutputInterface $output)
    {
        // Choices
        $options = $output->ask('Choose field options (e.g. list.option1,list.option2)');

        $choices = array_map(
            function ($value) {
                return trim($value);
            },
            explode(",", $options)
        );

        $field->data->choices = $choices;
    }
}
