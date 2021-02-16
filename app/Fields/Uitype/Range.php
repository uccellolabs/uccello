<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Fields\Traits\UccelloUitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class Range implements Uitype
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
        return 'range';
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
        $options = [
            'attr' => [
                'min' => $field->data->min ?? 0,
                'max' => $field->data->max ?? 100,
                'step' => $field->data->step ?? 1,
            ],
            // 'value' => $field->uitype->getFormattedValueToDisplay($field, $record) ?? $field->data->start ?? $field->data->min ?? 0,
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
                'key' => 'min',
                'label' => trans('uccello::uitype.option.range.min'),
                'type' => 'number',
                'default' => 0,
            ],
            [
                'key' => 'max',
                'label' => trans('uccello::uitype.option.range.max'),
                'type' => 'number',
                'default' => 100,
            ],
            [
                'key' => 'step',
                'label' => trans('uccello::uitype.option.range.step'),
                'required' => true,
                'type' => 'number',
                'default' => 1,
            ],
            [
                'key' => 'repeated',
                'label' => trans('uccello::uitype.option.common.repeated'),
                'type' => 'boolean'
            ]
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
        $field->data->min = (int)$output->ask('What is the minimum value?', 0);

        // Maximum value
        $field->data->max = (int)$output->ask('What is the maximum value?', 100);

        // Increment
        $field->data->step = (int)$output->ask('What is the increment?', 1);

        // Initial values
        // $field->data->start = $output->ask('Initial values (can be multiple) e.g. [10,30]', '[0]');

        // // Maximum gap
        // $limit = $output->ask('What is the maximum gap between two values?');
        // if (!is_null($limit)) {
        //     $field->data->limit = (int)$limit;
        // }

        // // Minimum gap
        // $margin = (int)$output->ask('What is the minimum gap between two values?');
        // if (!is_null($margin)) {
        //     $field->data->margin = (int)$margin;
        // }
    }
}
