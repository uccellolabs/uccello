<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class Password extends Text implements Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @return string
     */
    public function getFormType() : string
    {
        return 'password';
    }

    /**
     * Returns default icon.
     *
     * @return string|null
     */
    public function getDefaultIcon() : ?string
    {
        return 'lock';
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
        return [
            'attr' => [
                'autocomplete' => 'new-password' // Stop browser auto-fill
            ]
        ];
    }

    /**
     * Return options for Module Designer
     *
     * @return array
     */
    public function getFieldOptions() : array
    {
        return [
            'repeated' => [
                'type' => 'boolean',
                'default_value' => true,
            ],
        ];
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
        return Hash::make($value);
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
        return '********';
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
        $repeated = $output->confirm('Would you like to repeat this field (for confirmation)?', true);

        if ($repeated) {
            $field->data->repeated = true;
        }
    }

    /**
     * Returns some specific options relative to the field, used by uccello/module-designer
     *
     * @return array
     */
    public function moduleDesignerConfig()
    {
        return [
            [
                'label' => trans('uccello::config.uitypes.global.repeat_field'),
                'type' => 'boolean',
                'attribute' => 'repeated',
                'default' => true,
            ],
        ];
    }
}