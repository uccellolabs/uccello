<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class Currency extends Number implements Uitype
{
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
        return [
            'default_value' => request($field->name) ?? $field->data->default ?? 0,
        ];
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
                'key' => 'precision',
                'label' => trans('uccello::uitype.option.currency.precision'),
                'type' => 'number',
                'default' => 2,
            ],
            [
                'key' => 'symbol',
                'label' => trans('uccello::uitype.option.currency.symbol'),
                'type' => 'text'
            ],
            [
                'key' => 'symbol_position',
                'label' => trans('uccello::uitype.option.currency.symbol_position'),
                'type' => 'select',
                'choices' => [
                    [
                        'value' => 'left',
                        'label' => trans('uccello::uitype.option.currency.symbol_position_left'),
                    ],
                    [
                        'value' => 'right',
                        'label' => trans('uccello::uitype.option.currency.symbol_position_right'),
                    ]
                ]
            ],
        ];
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
        $precision = $field->data->precision ?? 0;

        $value = $record->{$field->column};

        if ($value) {
            $value = number_format($value, $precision);
        }
        $symbol = $field->data->symbol ?? '';

        if (optional($field->data)->symbol_position === 'left') {
            $value = "$symbol $value";
        } else {
            $value = "$value $symbol";
        }

        return $value;
    }

    /**
     * Ask the user some specific options relative to a field
     *
     * @param \StdClass $module
     * @param \StdClass $field
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Input\OutputInterface $output
     * @return void
     */
    public function askFieldOptions(\StdClass &$module, \StdClass &$field, InputInterface $input, OutputInterface $output)
    {
        // Precision
        $field->data->precision = (int)$output->ask('What is the precision?', 2);

        // Symbol
        $field->data->symbol = (int)$output->ask('What is the symbol?', '$');

        // Symbol position
        $field->data->symbol_position = (int)$output->choice('What is the symbol position?', ['left', 'right'], 'left');
    }

    /**
     * Create field column in the module table
     *
     * @param \Uccello\Core\Models\Field $field
     * @param \Illuminate\Database\Schema\Blueprint $table
     * @return \Illuminate\Support\Fluent
     */
    public function createFieldColumn(Field $field, Blueprint $table) : Fluent
    {
        return $table->decimal($this->getDefaultDatabaseColumn($field), 13, 4);
    }

    /**
     * Get field column creation in string format (for make:module)
     *
     * @param \Uccello\Core\Models\Field $field
     * @return string
     */
    public function createFieldColumnStr(Field $field) : string
    {
        $column = $this->getDefaultDatabaseColumn($field);
        return "\$table->decimal('$column', 13, 4)";
    }
}
