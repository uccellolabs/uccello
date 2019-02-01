<?php

namespace Uccello\Core\Fields\Uitype;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Fluent;
use Uccello\Core\Contracts\Field\Uitype as UitypeInterface;
use Uccello\Core\Fields\Traits\UccelloUitype;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Uitype;

class Reference implements UitypeInterface
{
    use UccelloUitype;

    /**
     * Returns field type used by Form builder.
     *
     * @param \Uccello\Core\Models\Field $field
     * @return string
     */
    public function getFormType(Field $field) : string
    {
        $referenceField = $this->getReferenceField($field);

        return $this->getUitypeInstance($referenceField)->getFormType($referenceField);
    }
    /**
     * Returns options for Form builder.
     *
     * @param mixed $record
     * @param \Uccello\Core\Models\Field $field
     * @param \Uccello\Core\Models\Module $module
     * @return array
     */
    public function getFormOptions($record, Field $field, Module $module) : array
    {
        $referenceField = $this->getReferenceField($field);
        $referenceRecord = $this->getReferenceRecord($field, $record);

        return $this->getUitypeInstance($referenceField)->getFormOptions($referenceRecord, $referenceField, $module);
    }

    /**
     * Returns default database column name.
     *
     * @return string
     */
    public function getDefaultDatabaseColumn(Field $field) : string
    {
        $referenceField = $this->getReferenceField($field);

        return $this->getUitypeInstance($referenceField)->getDefaultDatabaseColumn($referenceField);
    }

    /**
     * Returns default icon.
     *
     * @param \Uccello\Core\Models\Field $field
     * @return string|null
     */
    public function getDefaultIcon(Field $field) : ?string
    {
        $referenceField = $this->getReferenceField($field);

        return $this->getUitypeInstance($referenceField)->getDefaultIcon($field);
    }

    /**
     * Returns default value.
     *
     * @param \Uccello\Core\Models\Field $field
     * @param mixed $record
     * @return mixed|null
     */
    public function getDefaultValue(Field $field, $record)
    {
        // Get reference data
        $referenceField = $this->getReferenceField($field);
        $referenceRecord = $this->getReferenceRecord($field, $record);

        return $referenceRecord->{$referenceField->column} ?? $this->getUitypeInstance($referenceField)->getDefaultValue($field, $referenceRecord);
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
        $referenceField = $this->getReferenceField($field);
        $referenceRecord = $this->getReferenceRecord($field, $record);

        return $this->getUitypeInstance($referenceField)->getFormattedValueToDisplay($referenceField, $referenceRecord);
    }

    /**
     * Returns formatted value to save.
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
        $referenceField = $this->getReferenceField($field);
        $referenceRecord = $this->getReferenceRecord($field, $record);

        return $this->getUitypeInstance($referenceField)->getFormattedValueToSave($request, $referenceField, $value, $referenceRecord, $domain, $module);
    }

    /**
     * Returns formatted value to search.
     * By default adds % at the beginning end the ending to make a 'like' query.
     *
     * @param \Uccello\Core\Models\Field $field
     * @param mixed $value
     * @return string
     */
    public function getFormattedValueToSearch(Field $field, $value) : string
    {
        $referenceField = $this->getReferenceField($field);

        return $this->getUitypeInstance($referenceField)->getFormattedValueToSearch($field, $value);
    }

    /**
     * Returns updated query after adding a new search condition.
     *
     * @param \Illuminate\Database\Eloquent\Builder query
     * @param \Uccello\Core\Models\Field $field
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function addConditionToSearchQuery(Builder $query, Field $field, $value) : Builder
    {
        $referenceField = $this->getReferenceField($field);

        return $this->getUitypeInstance($referenceField)->addConditionToSearchQuery($query, $referenceField, $value);
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
        // Reference module
        $modules = Module::orderBy('name')->get();

        $choices = [ ];
        foreach ($modules as $_module) {
            $choices[ ] = $_module->name;
        }

        $referenceModule = $output->choice('What is the reference module?', $choices);

        // Reference field
        $fields = Field::where('module_id', ucmodule($referenceModule)->id)->orderBy('name')->get();

        $choices = [ ];
        foreach ($fields as $_field) {
            $choices[ ] = $_field->name;
        }

        $referenceField = $output->choice('What is the reference field?', $choices);

        // Reference source
        $referenceSource = $output->ask('What is the reference source? (e.g: record:id, auth:id, fieldname)');

        $field->data->reference = "$referenceModule,$referenceField,$referenceSource";
    }

    /**
     * Create field column in the module table
     *
     * @param \Uccello\Core\Models\Field $field
     * @param \Illuminate\Database\Schema\Blueprint $table
     * @return \Illuminate\Support\Fluent
     */
    public function createFieldColumn(Field $field, Blueprint $table) : Fluent
    {
        $referenceField = $this->getReferenceField($field);

        return $this->getUitypeInstance($referenceField)->createFieldColumn($referenceField, $table);
    }

    /**
     * Get field column creation in string format
     *
     * @param \Uccello\Core\Models\Field $field
     * @return string
     */
    public function createFieldColumnStr(Field $field) : string
    {
        $referenceField = $this->getReferenceField($field);

        return $this->getUitypeInstance($referenceField)->createFieldColumnStr($referenceField);
    }

    /**
     * Get an instance of the uitype used by a field
     *
     * @param Field $field
     * @return mixed
     */
    public function getUitypeInstance(Field $field)
    {
        $uitypeClass = $field->uitype->class;

        return new $uitypeClass();
    }

    /**
     * Get reference field.
     *
     * @param \Uccello\Core\Models\Field
     * @return \Uccello\Core\Models\Field|null
     */
    public function getReferenceField(Field $field) : ?Field
    {
        $referenceField = null;

        if (!empty($field->data->reference)) {
            $referenceData = explode(',', $field->data->reference);

            if (count($referenceData) >= 2) {
                // Reference module name
                $referenceModuleName = trim($referenceData[0]);

                // Reference field name
                $referenceFieldName = trim($referenceData[1]);

                // Reference module
                $referenceModule = ucmodule($referenceModuleName);

                if (!empty($referenceModule)) {
                    // Reference field
                    $referenceField = $referenceModule->getField($referenceFieldName);
                }
            }
        }

        return $referenceField;
    }

    public function getReferenceUitype(Field $field) : ?Uitype
    {
        return $this->getReferenceField($field)->uitype ?? null;
    }

    public function getReferenceModule(Field $field) : ?Module
    {
        $referenceModule = null;

        if (!is_null($this->getReferenceField($field))) {
            $referenceData = explode(',', $field->data->reference);

            if (count($referenceData) >= 2) {
                $referenceModule = ucmodule(trim($referenceData[0]));
            }
        }

        return $referenceModule;
    }


    /**
     * Returns a string which represents the source of the record that will be updated when saving.
     * It can be:
     * - record:id - It will be replaced by the id of the record displayed
     * - auth:id - It will be replaced by the id of the authenticated user
     * - fieldname - It will be replaced by the value of the field called 'fieldname' of the record displayed. Replace by the good field's name.
     *
     * @return string|null
     */
    public function getReferenceRecordSource(Field $field) : ?string
    {
        $referenceRecordSource = null;

        if (!is_null($this->getReferenceField($field))) {
            $referenceData = explode(',', $field->data->reference);

            if (count($referenceData) >= 3) {
                $referenceRecordSource = trim($referenceData[2]);
            }
        }

        return $referenceRecordSource;
    }

    public function getReferenceRecord(Field $field, $record)
    {
        $referenceRecord = null;

        $referenceField = $this->getReferenceField($field);
        $referenceRecordSource = $this->getReferenceRecordSource($field);

        if (!is_null($referenceField) && !is_null($referenceRecordSource)) {
            $referenceRecordId = null;

            switch ($referenceRecordSource) {
                // Current record id
                case 'record:id':
                        $referenceRecordId = $record->{$record->getKeyName()};
                    break;

                // Authenticated user id
                case 'auth:id':
                        $referenceRecordId = auth()->id();
                    break;

                // Field name of the current record
                default:
                        $referenceRecordId = $record->{$referenceRecordSource};
                    break;
            }

            // Try to retrieve the source record
            if (!is_null($referenceRecordId)) {
                $sourceModule = $referenceField->module;
                $sourceModelClass = $sourceModule->model_class;

                if (!is_null($sourceModelClass)) {
                    $referenceRecord = $sourceModelClass::find($referenceRecordId);
                }
            }
        }

        return $referenceRecord;
    }
}