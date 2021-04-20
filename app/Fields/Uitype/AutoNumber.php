<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Fields\Traits\UccelloUitype;
use Uccello\Core\Models\Field;

class AutoNumber extends Hidden implements Uitype
{
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
                'key' => 'start_value',
                'label' => trans('uccello::uitype.option.auto_number.start_value'),
                'type' => 'number',
                'default' => 1,
            ],
            [
                'key' => 'increment',
                'label' => trans('uccello::uitype.option.auto_number.increment'),
                'type' => 'number',
                'default' => 1,
            ],
            [
                'key' => 'strlen_min',
                'label' => trans('uccello::uitype.option.auto_number.strlen_min'),
                'type' => 'number',
                'default' => 1,
            ],
            [
                'key' => 'prefix',
                'label' => trans('uccello::uitype.option.auto_number.prefix'),
                'type' => 'text',
            ],
            [
                'key' => 'suffix',
                'label' => trans('uccello::uitype.option.auto_number.suffix'),
                'type' => 'text',
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
        // Start value
        $field->data->start_value = $output->ask('What is the start value?', 1);

        // Increment
        $field->data->start_value = $output->ask('What is the increment?', 1);

        // Strlen min
        $field->data->strlen_min = $output->ask('What is the number min string length?', 1);

        // Prefix
        $field->data->prefix = $output->ask('What is the prefix?');

        // Suffix
        $field->data->suffix = $output->ask('What is the suffix?');
    }
}
