<?php

namespace Uccello\Core\Forms;

use Kris\LaravelFormBuilder\Form As DefaultForm;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;

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

        // Filter values
        $values = array_filter($values, function ($value) {
            return !is_null($value) && (!is_object($value) || !$value instanceof UploadedFile) && !is_array($value);
        });

        // Set fields value only if the column exists in the table
        foreach ($values as $name => $value) {
            if (Schema::hasColumn($this->getModel()->getTable(), $name)) {
                $this->getModel()->$name = $value;
            }
        }

        parent::redirectIfNotValid($destination);
    }
}