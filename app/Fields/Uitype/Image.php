<?php

namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Contracts\Field\Uitype;
use Illuminate\Http\Request;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Storage;

class Image extends File implements Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @return string
     */
    public function getFormType() : string
    {
        return 'file';
    }

    /**
     * Returns default icon.
     *
     * @return string|null
     */
    public function getDefaultIcon() : ?string
    {
        return 'image';
    }

    /**
     * Returns formatted value to display.
     *
     * @param \Uccello\Core\Models\Field $field
     * @param mixed $record
     * @return string
     */
    public function getFormattedValueToDisplay(Field $field, $record) : string
    {
        $value = $record->{$field->column} ?? '';

        if ($value) {
            $value = Storage::url($value);
        }

        return  $value;
    }

    /**
     * Returns formatted value to save.
     * Upload file if defined and get its path, or delete file.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Uccello\Core\Models\Field $field
     * @param mixed|null $value
     * @param mixed|null $record
     * @param \Uccello\Core\Models\Domain|null $domain
     * @param \Uccello\Core\Models\Module|null $module
     * @return string|null
     */
    public function getFormattedValueToSave(Request $request, Field $field, $value, $record = null, ?Domain $domain = null, ?Module $module = null) : ?string
    {
        //TODO: Delete old file

        // Update file
        if ($request->file($field->name)) {

            // Field data
            $fieldData = $field->data;

            // Make directory path
            $directoryPath = 'public/'; // Public
            $directoryPath .= isset($domain) ? $domain->slug.'/' : ''; // Domain
            $directoryPath .= isset($fieldData->path) ? trim($fieldData->path, '/') : ''; // Custom directory

            // Save file
            $path = Storage::putFile($directoryPath, $request->file($field->name));
            $value = $path;

        }
        // Delete file
        elseif ($request->input('delete-'.$field->name)) {
            $value = null;

        }
        // Neither update nor delete
        elseif ($record && $record->{$field->column}) {
            $value = $record->{$field->column};
        }

        return $value;
    }
}