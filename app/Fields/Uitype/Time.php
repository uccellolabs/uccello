<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Fluent;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class Time extends DateTime implements Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @return string
     */
    public function getFormType() : string
    {
        return 'time';
    }

    /**
     * Returns default icon.
     *
     * @return string|null
     */
    public function getDefaultIcon() : ?string
    {
        return 'access_time';
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
        $options[ 'attr' ] = [
            'class' => 'timepicker',
            'placeholder' => '12:00',
            'data-format' => config('uccello.format.js.time'),
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
                'key' => 'repeated',
                'label' => trans('uccello::uitype.option.common.repeated'),
                'type' => 'boolean'
            ]
        ];
    }

    /**
     * Return options for Import
     *
     * @param object $bundle
     *
     * @return array
     */
    public function getFieldOptionsForImport($bundle) : array
    {
        return [
            [
                'key' => 'format',
                'label' => trans('uccello::uitype.import_option.time.format'),
                'type' => 'text',
                'placeholder' => 'H:i:s',
            ]
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
        return $record->{$field->column}
            ? (new \Carbon\Carbon($record->{$field->column}))->format(config('uccello.format.php.time'))
            : '';
    }

    /**
     * Returns formatted value to save.
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
        $repeated = $output->confirm('Would you like to repeat this field (for confirmation)?', false);

        if ($repeated) {
            $field->data->repeated = true;
        }
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
        return $table->time($this->getDefaultDatabaseColumn($field));
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
        return "\$table->time('$column')";
    }
}
