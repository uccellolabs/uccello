<?php

namespace Uccello\Core\Forms;

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
        $recordId = $this->getModel()->getKey() ?? null;
        if ($recordId ?? false) {
            $routeParams['id'] = $recordId;
        }

        // Get mode
        $mode = $recordId ? 'edit' : 'create';

        // Options
        $this->formOptions = [
            'method' => 'POST', // Use POST method
            'url' => ucroute('uccello.save', $domain, $module, $routeParams), // URL to call
            'class' => 'edit-form',
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

            // Get field options
            $fieldOptions = $this->getFieldOptions($field);

            // Add field to form
            $this->add($field->name, $fieldType, $fieldOptions);
        }

        // Add a save button
        $this->add('save_btn', 'submit', [
            'label' => '<i class="material-icons">save</i>',
            'attr' => [
                'class' => 'btn bg-green btn-circle-lg waves-effect waves-circle waves-float btn-save',
                'title' => uctrans('button.save', $module),
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
            ]
        ]);

        // Add a save and new button
        $this->add('save_new_btn', 'button', [
            'label' => '<i class="material-icons">add</i>',
            'attr' => [
                'class' => 'btn bg-primary btn-circle-lg waves-effect waves-circle waves-float btn-save-new',
                'title' => uctrans('button.save_new', $module),
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
            ]
        ]);

        // Add a save and new hidden value
        $this->add('save_new_hdn', 'hidden');
    }

    /**
     * Returns field type used by Form builder.
     *
     * @param Field $field
     * @return string
     */
    public function getFormBuilderType(Field $field): string
    {
        $uitype = $this->getUitypeInstance($field);

        return $uitype->getFormType();
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

        // Check if required CSS class must be added
        $requiredClass = $field->required ? 'required' : '';

        $options = [
            'label' => uctrans($field->label, $module),
            'label_attr' => ['class' => 'form-label' . $requiredClass],
            'rules' => $this->getFieldRules($field),
            'attr' => [
                'class' => 'form-control'
            ]
        ];

        // Set default value only if it is a creation (record id doen't exist)
        if (is_null($this->getModel()->getKey())) {
            $options['default_value'] = $field->data->default ?? null;
        }

        // Add other options
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
        // Get module data
        $module = $this->getData('module');

        $uitype = $this->getUitypeInstance($field);

        return $uitype->getFormOptions($this->getModel(), $field, $module);
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
    protected function getFieldRules(Field $field) : ?array
    {
        $rules = null;

        if (!empty($field->data->rules)) {
            // Get the rules
            $rules = $field->data->rules;

            // Check if we are editing an existant record
            $record = $this->getModel();

            if (!is_null($record->getKey())) {
                // Replace %id% by the record id
                $rules = preg_replace('`%id%`', $record->getKey(), $rules);
            } else {
                // Remove ,%id% from the rules
                $rules = preg_replace('`,%id%`', '', $rules);
            }
        }

        return explode('|', $rules); // We transform into array because specify validation rules with regex separated by pipeline can lead to undesired behavior (see: https://stackoverflow.com/questions/42577045/laravel-5-4-validation-with-regex)
    }

    /**
     * Get an instance of the uitype used by a field
     *
     * @param Field $field
     * @return mixed
     */
    protected function getUitypeInstance(Field $field)
    {
        $uitypeClass = $field->uitype->class;

        return new $uitypeClass();
    }
}
