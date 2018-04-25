<?php

namespace Sardoj\Uccello\Forms;

use Kris\LaravelFormBuilder\Form;
use Debugbar;
use Sardoj\Uccello\Field;

class EditForm extends Form
{
    /**
     * Build the form.
     *
     * @return void
     */
    public function buildForm()
    {
        // Get module data
        $module = $this->getData('module');

        // Deactivate HTML5 validation
        $this->formOptions = ['novalidate'];

        // Add all fields
        foreach($module->fields as $field)
        {
            // Get translated field label
            $fieldLabel = __(sprintf('uccello::%s.%s', $module->name, $field->label));

            // Repeated field
            if ($field->data->repeated ?? false) {
                $this->add($field->name, 'repeated', [
                    'type' => $field->uitype,                  
                    'first_options' => [
                        'label' => $fieldLabel,
                        'label_attr' => ['class' => 'form-label'],
                        'rules' => $field->data->rules ?? null,
                        'default_value' => $field->data->default ?? null,
                        'attr' => [
                            'class' => 'form-control'
                        ],
                    ],
                    'second_options' => [
                        'label' => __(sprintf('uccello::%s.%s', $module->name, $field->label . '_confirmation')),
                        'label_attr' => ['class' => 'form-label'],
                        'default_value' => $field->data->default ?? null,
                        'attr' => [
                            'class' => 'form-control'
                        ],
                    ]
                ]);
            }
            // Classic field
            else {
                $this->add($field->name, $field->uitype, [
                    'label' => $fieldLabel,
                    'label_attr' => ['class' => 'form-label'],
                    'rules' => $field->data->rules ?? null,
                    'default_value' => $field->data->default ?? null,
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]);
            }
        }

        // Add a save button
        $this->add('submit_btn', 'submit', [
            'label' => __('uccello::global.button.save'),
            'attr' => [
                'class' => 'btn btn-success pull-right'
            ]
        ]);
    }
}
