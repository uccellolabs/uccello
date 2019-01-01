<?php

namespace Uccello\Core\Forms;

use Kris\LaravelFormBuilder\Form As DefaultForm;

class Form extends DefaultForm
{
    /**
     * Makes the record from fields values and redirects to a destination when form is invalid.
     *
     * @param  string|null $destination The target url.
     * @return HttpResponseException
     */
    public function redirectIfNotValid($destination = null)
    {
        $values = $this->getFieldValues();

        $domain = $this->getData('domain');
        $module = $this->getData('module');
        $record = $this->getModel();
        $request = $this->getRequest();

        foreach ($values as $fieldName => $value) {
            $field = $module->getField($fieldName);

            // If the field exists format the value and store it in the good model column
            if (!is_null($field)) {
                $column = $field->column;
                $this->getModel()->$column = $field->uitype->getFormattedValueToSave($request, $field, $value, $record, $domain, $module);
            }
        }

        parent::redirectIfNotValid($destination);
    }
}