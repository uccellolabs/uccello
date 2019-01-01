<?php

namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Fields\Traits\UccelloUitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @param \Uccello\Core\Models\Module $module
     * @return array
     */
    public function getFormOptions($record, Field $field, Module $module) : array
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
            'empty_value' => uctrans('select_empty_value', $module),
            'attr' => [
                'class' => 'form-control show-tick',
                'data-live-search' => 'true'
            ],
        ];

        return $options;
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
            function($value) {
                return trim($value);
            },
            explode(",", $options)
        );

        $field->data->choices = $choices;
    }
}