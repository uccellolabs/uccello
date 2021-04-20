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

class Percent extends Number implements Uitype
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
            'attr' => [
                'data-precision' => $field->data->precision ?? 2,
            ],
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
                'label' => trans('uccello::uitype.option.percent.precision'),
                'type' => 'number',
                'default' => 0,
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

        $value = round($record->{$field->column}, $precision) ?? '';

        return $value.' %';
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
        $leftPartLength = 11;
        $rightPartLength = isset($field->data->precision) && $field->data->precision > 2 ? $field->data->precision : 2; // At leat 2
        $totalLength = $leftPartLength + $rightPartLength;

        return $table->decimal($this->getDefaultDatabaseColumn($field), $totalLength, $rightPartLength);
    }

    /**
     * Get field column creation in string format (for make:module)
     *
     * @param \Uccello\Core\Models\Field $field
     * @return string
     */
    public function createFieldColumnStr(Field $field) : string
    {
        $leftPartLength = 11;
        $rightPartLength = isset($field->data->precision) && $field->data->precision > 2 ? $field->data->precision : 2; // At least 2
        $totalLength = $leftPartLength + $rightPartLength;

        $column = $this->getDefaultDatabaseColumn($field);
        return "\$table->decimal('$column', $totalLength, $rightPartLength)";
    }
}
