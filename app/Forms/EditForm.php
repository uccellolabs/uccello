<?php

namespace Uccello\Core\Forms;

use Illuminate\Support\Facades\Hash;
use Uccello\Core\Models\Field;
use Uccello\Core\Facades\Uccello;

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

        // Make route params
        $routeParams = [];

        // Get and add record id to route params if defined
        $recordId = $this->getModel()->id ?? null;
        if ($recordId ?? false) {
            $routeParams['id'] = $recordId;
        }

        // Get mode
        $mode = $recordId ? 'edit' : 'create';

        // Options
        $this->formOptions = [
            'method' => 'POST', // Use POST method
            'url' => ucroute('uccello.save', $domain, $module, $routeParams), // URL to call
            'novalidate', // Deactivate HTML5 validation
        ];

        // Add all fields
        foreach ($module->fields as $field)
        {
            // Check if the field can be displayed
            if (($mode === 'edit' && !$field->isEditable()) || ($mode === 'create' && !$field->isCreateable())) {
                continue;
            }

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
     * Optionally mess with this form's $values before it's returned from getFieldValues().
     *
     * @param array $values
     * @return void
     */
    public function alterFieldValues(array &$values)
    {
        // Get module data
        $module = $this->getData('module');

        foreach ($module->fields as $field)
        {
            // Hash passwords
            if ($field->uitype === Field::UITYPE_PASSWORD) {
                if (!empty($values[$field->name])) {
                    $values[$field->name] = Hash::make($values[$field->name]);
                }
            }
        }

        // dd($values);
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
            'rules' => $this->getFieldRules($field),
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
                        'class' => Uccello::getModelClassByModuleName($field->data->module),
                        'property' => $field->data->field ?? 'name',
                        'empty_value' => uctrans('select_empty_value', $module)
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

        // Second field have default options too, except label and rules (already verified in the first field)
        $secondFieldOptions = $firstFieldOptions;
        $secondFieldOptions['label'] = uctrans($field->label.'_confirmation', $module);
        $secondFieldOptions['rules'] = null;

        return [
            'type' => $this->getFormBuilderType($field),
            'first_options' => $firstFieldOptions,
            'second_options' => $secondFieldOptions
        ];
    }

    /**
     * Returns the rules defined for a field.
     * In the rules %id% is replaced by the record id (usefull for unique key control).
     *
     * @param Field $field
     * @return string|null
     */
    protected function getFieldRules(Field $field) : ?string
    {
        $rules = null;

        if (!empty($field->data->rules)) {
            // Get the rules
            $rules = $field->data->rules;

            // Check if we are editing an existant record
            $record = $this->getModel();

            if (!is_null($record->id)) {
                // Replace %id% by the record id
                $rules = preg_replace('`%id%`', $record->id, $rules);
            } else {
                // Remove ,%id% from the rules
                $rules = preg_replace('`,%id%`', '', $rules);
            }
        }

        return $rules;
    }
}
