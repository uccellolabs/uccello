<?php

namespace Uccello\Core\Fields\Uitype;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Fields\Traits\UccelloUitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class File implements Uitype
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
            $directoryPath = isset($fieldData->public) && $fieldData->public === true ? 'public/' : ''; // Public or Private
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

        // Field data
        $fieldData = $field->data;

        // If the file is defined and is public, get its url
        if ($value && isset($fieldData->public) && $fieldData->public === true) {
            $value = Storage::url($value);
        }

        return  $value;
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
        // Path
        $path = $output->ask('What is the target path? (Only if you want to create subdirectory)', null);
        if (!is_null($path)) {
            $field->data->path = $path;
        }

        // Pubic
        $public = $output->confirm('Can the uploaded files be accessed from the outside?', false);
        if ($public) {
            $field->data->public = true;
        }
    }
}