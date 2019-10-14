<?php

namespace Uccello\Core\Fields\Uitype;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Fields\Traits\UccelloUitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class Month implements Uitype
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
        return 'text';
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
        $options[ 'attr' ] = [ 'class' => 'monthpicker', 'autocomplete' => 'off' ];

        return $options;
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
                'default_value' => false,
            ],
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
        $repeated = $output->confirm('Would you like to repeat this field (for confirmation)?', false);

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
                'default' => false,
            ],
        ];
    }
}