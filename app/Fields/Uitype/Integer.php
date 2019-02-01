<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Fluent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;

class Integer extends Number implements Uitype
{
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
        return [
            'attr' => [
                'class' => 'form-control',
                'data-min' => $field->data->min ?? null,
                'data-max' => $field->data->max ?? null,
                'data-precision' => 0,
                'data-step' => $field->data->step ?? 1,
                'autocomplete' => 'off',
            ],
            'default_value' => request($field->name) ?? $this->getDefaultValue($field, $record) ?? 0,
        ];
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
        // Minimum value
        $field->data->min = (int)$output->ask('What is the minimum value?');

        // Maximum value
        $field->data->max = (int)$output->ask('What is the maximum value?');

        // Increment
        $field->data->step = (int)$output->ask('What is the increment?', 1);
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
        return $table->integer($this->getDefaultDatabaseColumn($field));
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
        return "\$table->integer('$column')";
    }
}