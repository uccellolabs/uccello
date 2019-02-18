<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Fluent;
use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;

class Date extends DateTime implements Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @return string
     */
    public function getFormType() : string
    {
        return 'text';
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
        $options = parent::getFormOptions($record, $field, $module);

        $options[ 'attr' ] = [ 'class' => 'form-control datepicker', 'autocomplete' => 'off' ];

        return $options;
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
        $query->where(function ($query) use($field, $value) {
            $values = explode(',', $value); // Start Date, End Date
            $query->whereBetween($field->column, [ trim($values[0]), trim($values[1]) ])->get();
        });

        return $query;
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
        return $table->date($this->getDefaultDatabaseColumn($field));
    }

    /**
     * Get field column creation in string format (for make:module)
     *
     * @param \Uccello\Core\Models\Field $field
     * @return string
     */
    public function createFieldColumnStr(Field $field) : string
    {
        $column = $this->getDefaultDatabaseColumn($field);
        return "\$table->date('$column')";
    }
}