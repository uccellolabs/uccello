<?php

namespace Uccello\Core\Forms;

use Uccello\Core\Models\Field;
use Uccello\Core\Facades\Uccello;

class PopupEditForm extends EditForm
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

        // Get request data
        $request = $this->getData('request');

        $fieldName = $request->get('field');

        // Make route params
        $routeParams = [ ];

        // Get mode
        $mode = 'create';

        // Options
        $this->formOptions = [
            'method' => 'POST', // Use POST method
            'url' => ucroute('uccello.popup.save', $domain, $module, $routeParams), // URL to call
            'class' => 'edit-form',
            'novalidate', // Deactivate HTML5 validation
            'id' => 'form_popup_'.$fieldName
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
            'label' => '<i class="material-icons left">save</i> '.uctrans('button.save', $module),
            'attr' => [
                'class' => 'btn waves-effect green right',
            ]
        ]);
    }
}
