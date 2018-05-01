<?php

namespace Sardoj\Uccello\Forms;

use Kris\LaravelFormBuilder\Form;
use Sardoj\Uccello\Field;
use Debugbar;

class EditForm extends Form
{
    /**
     * Build the form.
     *
     * @return void
     */
    public function buildForm()
    {
        // Get domain data
        $domain = $this->getData('domain');

        // Get module data
        $module = $this->getData('module');

        // Options
        $this->formOptions = [
            'method' => 'POST', // Use POST method
            'url' => route('store', ['domain' => $domain->slug, 'module' => $module->name]), // URL to call      
            'novalidate', // Deactivate HTML5 validation
        ];

        // Add all fields
        foreach($module->fields as $field)
        {
            // Get field type: if the field must be repeated, the type is "repeated" else get the FormBuilder type
            $fieldType = isset($field->data->repeated) && $field->data->repeated === true ? 'repeated' : $this->getFormBuilderType($field);

            // Get translated field label
            $fieldLabel = uctrans($field->label, $module);

            // Get field options
            $fieldOptions = $this->getFieldOptions($field);

            // Add field to form
            $this->add($field->name, $fieldType, $fieldOptions);
        }

        // Add a save button
        $this->add('submit_btn', 'submit', [
            'label' => uctrans('button.save', $module),
            'attr' => [
                'class' => 'btn btn-success pull-right'
            ]
        ]);
    }

    /**
     * Return field type used by Form builder according to uitype.
     *
     * @param Field $field
     * @return string
     */
    public function getFormBuilderType(Field $field): string
    {
        $type = "";

        switch ($field->uitype) {
            case Field::UITYPE_EMAIL:
                $type = 'email';
                break;

            case Field::UITYPE_PASSWORD:
                $type = 'password';
                break;

            case Field::UITYPE_HIDDEN:
                $type = 'hidden';
                break;

            case Field::UITYPE_TEXTAREA:
                $type = 'textarea';
                break;

            case Field::UITYPE_NUMBER:
            case Field::UITYPE_INTEGER:
                $type = 'number';
                break;

            case Field::UITYPE_FILE:
                $type = 'file';
                break;

            case Field::UITYPE_IMAGE:
                $type = 'image';
                break;

            case Field::UITYPE_URL:
                $type = 'url';
                break;

            case Field::UITYPE_PHONE:
                $type = 'tel';
                break;

            case Field::UITYPE_SEARCH:
                $type = 'search';
                break;

            case Field::UITYPE_COLOR:
                $type = 'color';
                break;

            case Field::UITYPE_DATE:
                $type = 'date';
                break;

            case Field::UITYPE_TIME:
                $type = 'time';
                break;

            case Field::UITYPE_DATETIME:
                $type = 'datetime-local';
                break;

            case Field::UITYPE_MONTH:
                $type = 'month';
                break;

            case Field::UITYPE_WEEK:
                $type = 'week';
                break;

            case Field::UITYPE_RANGE:
                $type = 'range';
                break;

            case Field::UITYPE_SELECT:
                $type = 'select';
                break;

            case Field::UITYPE_CHOICE:
                $type = 'choice';
                break;

            case Field::UITYPE_CHECKBOX:
            case Field::UITYPE_BOOLEAN:
                $type = 'checkbox';
                break;

            case Field::UITYPE_ENTITY:
                $type = 'entity';
                break;

            case Field::UITYPE_TEXT:
            default:
                $type = 'text';
                break;
        }

        return $type;
    }

    /**
     * Get field options according to its uitype and settings.
     *
     * @param Field $field
     * @return array
     */
    protected function getFieldOptions(Field $field): array
    {   
        $options = [];

        if ($field->data->repeated ?? false) {
            $options = $this->getRepeatedFieldOptions($field);
        } else {
            $options = $this->getDefaultFieldOptions($field);
        }

        return $options;
    }

    /**
     * Return default option for fields.
     *
     * @param Field $field
     * @return array
     */
    protected function getDefaultFieldOptions(Field $field): array
    {
        // Get module data
        $module = $this->getData('module');

        $options = [
            'label' => uctrans($field->label, $module),
            'label_attr' => ['class' => 'form-label'],
            'rules' => $field->data->rules ?? null,
            'default_value' => $field->data->default ?? null,
            'attr' => [
                'class' => 'form-control'
            ]
        ];

        $otherOptions = $this->getSpecialFieldOptions($field);        

        return array_merge($options, $otherOptions);
    }

    /**
     * Return options for special fields.
     *
     * @param Field $field
     * @return array
     */
    protected function getSpecialFieldOptions(Field $field): array
    {
        if (!is_object($field->data)) {
            return [];
        }

        // Get module data
        $module = $this->getData('module');

        $options = [];

        switch ($field->uitype) {
            // Entity
            case Field::UITYPE_ENTITY:
                if ($field->data->module) {
                    $options = [
                        'class' => ucgetEntityClassByModuleName($field->data->module),
                        'property' => $field->data->field ?? 'name'
                    ];
                }
                break;
            
            // Select
            case Field::UITYPE_SELECT:
                $choices = [];
                
                if ($field->data->choices) {
                    foreach ($field->data->choices as $choice) {
                        $choices[$choice] = uctrans($choice, $module);
                    }
                }

                $options = [
                    'choices' => $choices,
                    'selected' => $field->data->default ?? null,
                    'empty_value' => uctrans('select_empty_value', $module)
                ];
                break;
            
            // Choice
            case Field::UITYPE_CHOICE:
                $choices = [];
                
                if ($field->data->choices) {
                    foreach ($field->data->choices as $choice) {
                        $choices[$choice] = uctrans($choice, $module);
                    }
                }

                $options = [
                    'choices' => $choices,
                    'selected' => $field->data->default ?? null,
                    'expanded' => true,
                    'multiple' => $field->data->multiple ?? false
                ];
                break;
        }

        return $options;
    }

    /**
     * Return options for repeated fields.
     *
     * @param Field $field
     * @return array
     */
    protected function getRepeatedFieldOptions(Field $field): array
    {
        // Get module data
        $module = $this->getData('module');

        // First field have default options
        $firstFieldOptions = $this->getDefaultFieldOptions($field);

        // Second field have default options too, except the label
        $secondFieldOptions = $firstFieldOptions;
        $secondFieldOptions['label'] = uctrans($field->label.'_confirmation', $module);

        return [
            'type' => $this->getFormBuilderType($field),                  
            'first_options' => $firstFieldOptions,
            'second_options' => $secondFieldOptions
        ];
    }
}
