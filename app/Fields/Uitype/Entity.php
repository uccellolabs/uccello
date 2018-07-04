<?php

namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Fields\Traits\UccelloUitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;

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
     * @param Field $field
     * @param Module $module
     * @return array
     */
    public function getFormOptions(Field $field, Module $module): array
    {
        if (!is_object($field->data)) {
            return [];
        }

        $options = [];

        if ($field->data->module) {
            $options = [
                'class' => ucmodule($field->data->module)->model_class ?? null,
                'property' => $field->data->field ?? 'name',
                'empty_value' => uctrans('select_empty_value', $module)
            ];
        }

        return $options;
    }

    /**
     * Returns value to display.
     * Uses displayLabel attribute if defined, id else.
     *
     * @param Field $field
     * @param mixed $record
     * @return string
     */
    public function getDisplayedValue(Field $field, $record) : string
    {
        $relatedRecordId = $record->{$field->name};

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
            $value = $relatedRecord->displayLabel ?? $relatedRecord->id;
        } else { // Related record was probably deleted
            $value = '-';
        }

        return  $value;
    }
}