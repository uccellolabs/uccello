<?php

namespace Uccello\Core\Fields\Traits;

use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Domain;
use Illuminate\Database\Eloquent\Builder;


trait DefaultUitype
{
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
        return [];
    }

    /**
     * Returns default database column name.
     *
     * @return string
     */
    public function getDefaultDatabaseColumn(Field $field) : string
    {
        return $field->name;
    }

    /**
     * Returns default icon.
     *
     * @return string|null
     */
    public function getDefaultIcon() : ?string
    {
        return null;
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
        return $record->{$field->column} ?? '';
    }

    /**
     * Returns formatted value to save.
     *
     * @param Field $field
     * @param mixed|null $value
     * @param mixed|null $record
     * @param Domain|null $domain
     * @param Module|null $module
     * @return string|null
     */
    public function getFormattedValueToSave(Field $field, $value, $record=null, ?Domain $domain=null, ?Module $module=null) : ?string
    {
        return $value;
    }

    /**
     * Returns formatted value to search.
     * By default adds % at the beginning end the ending to make a 'like' query.
     *
     * @param mixed $value
     * @return string
     */
    public function getFormattedValueToSearch($value) : string
    {
        $formattedValue = $value;

        if ($formattedValue) {
            $formattedValue = "%$value%";
        }

        return $formattedValue;
    }

    /**
     * Returns updated query after adding a new search condition.
     *
     * @param Builder query
     * @param Field $field
     * @param mixed $value
     * @return Builder
     */
    public function addConditionToSearchQuery(Builder $query, Field $field, $value)
    {
        $formattedValue = $this->getFormattedValueToSearch($value);
        $query = $query->where($field->column, 'like', $formattedValue);

        return $query;
    }
}