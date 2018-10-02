<?php

namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Fields\Traits\UccelloUitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;
use Illuminate\Database\Eloquent\Builder;

class Entity implements Uitype
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
        return 'entity';
    }

    /**
     * Returns options for Form builder.
     *
     * @param mixed $record
     * @param Field $field
     * @param Module $module
     * @return array
     */
    public function getFormOptions($record, Field $field, Module $module): array
    {
        if (!is_object($field->data)) {
            return [];
        }

        $options = [];

        if ($field->data->module) {
            $options = [
                'class' => ucmodule($field->data->module)->model_class ?? null,
                'property' => $field->data->field ?? 'id',
                'empty_value' => uctrans('select_empty_value', $module),
                'selected' => $record->{$field->column} ?? null,
                'query_builder' => function ($relatedRecord) use($record) {
                    // If related record class is the same as the record one, ignore the current record
                    if (get_class($relatedRecord) === get_class($record)) {
                        return $relatedRecord->where($relatedRecord->getKeyName(), '!=', $record->getKey());
                    } else {
                        return $relatedRecord->all();
                    }
                },
            ];
        }

        return $options;
    }

    /**
     * Returns default database column name.
     *
     * @return string
     */
    public function getDefaultDatabaseColumn(Field $field) : string
    {
        return $field->name . '_id';
    }

    /**
     * Returns formatted value to display.
     * Uses recordLabel attribute if defined, id else.
     *
     * @param Field $field
     * @param mixed $record
     * @return string
     */
    public function getFormattedValueToDisplay(Field $field, $record) : string
    {
        $relatedRecordId = $record->{$field->column};

        if (!is_object($field->data) || !$field->data->module || !$relatedRecordId) {
            return '';
        }

        // Get related module
        $relatedModule = Module::where('name', $field->data->module)->first();

        // Get related record
        $relatedModelClass = $relatedModule->model_class;
        $relatedRecord = $relatedModelClass::find($relatedRecordId);

        // Check if there is an attribute called displayLabel in the related record else use id
        if (!is_null($relatedRecord)) {
            $value = $relatedRecord->recordLabel ?? $relatedRecord->getKey();
        } else { // Related record was probably deleted
            $value = '';
        }

        return  $value;
    }

    /**
     * Returns updated query after adding a new search condition.
     * Uses field data to know in which attribute make the search.
     *
     * @param Builder query
     * @param Field $field
     * @param mixed $value
     * @return Builder
     */
    public function addConditionToSearchQuery(Builder $query, Field $field, $value) : Builder
    {
        $formattedValue = $this->getFormattedValueToSearch($value);

        // Get field data
        $fieldData = $field->data;

        if (isset($fieldData->field)) {
            // Search by entity's main field (we suppose the belongsTo relation has the same name as the field)
            $query = $query->whereHas($field->name, function($q) use($field, $fieldData, $formattedValue) {
                $q->where($fieldData->field, 'like', $formattedValue);
            });
        }

        return $query;
    }
}