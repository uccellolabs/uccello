<?php

namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Fields\Traits\UccelloUitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class File implements Uitype
{
    use DefaultUitype;
    use UccelloUitype;

    /**
     * Returns field type used by Form builder.
     *
     * @return string
     */
    public function getFormType(): string
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
        return 'attachment';
    }

    /**
     * Returns formatted value to save.
     * Upload file if defined and get its path, or delete file.
     *
     * @param Request $request
     * @param Field $field
     * @param mixed|null $value
     * @param mixed|null $record
     * @param Domain|null $domain
     * @param Module|null $module
     * @return string|null
     */
    public function getFormattedValueToSave(Request $request, Field $field, $value, $record=null, ?Domain $domain=null, ?Module $module=null) : ?string
    {
        //TODO: Delete old file

        // Update file
        if ($request->file($field->name)) {

            // Field data
            $fieldData = $field->data;

            // Make directory path
            $directoryPath = isset($fieldData->public) && $fieldData->public === true ? 'public/' : ''; // Public or Private
            $directoryPath .= isset($domain) ? $domain->slug . '/' : ''; // Domain
            $directoryPath .= isset($fieldData->path) ? trim($fieldData->path, '/') : ''; // Custom directory

            // Save file
            $path = Storage::putFile($directoryPath, $request->file($field->name));
            $value = $path;

        }
        // Delete file
        elseif ($request->input('delete-' . $field->name)) {
            $value = null;

        }
        // Neither update nor delete
        elseif ($record && $record->{$field->column}) {
            $value = $record->{$field->column};
        }

        return $value;
    }

    /**
     * Returns formatted value to display.
     *
     * @param Field $field
     * @param mixed $record
     * @return string
     */
    public function getFormattedValueToDisplay(Field $field, $record) : string
    {
        $value = $record->{$field->column} ?? '';

        // Field data
        $fieldData = $field->data;

        // If the file is defined and is public, get its url
        if ($value && isset($fieldData->public) && $fieldData->public === true ) {
            $value = Storage::url($value);
        }

        return  $value;
    }
}