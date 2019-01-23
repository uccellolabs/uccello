<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Fluent;
use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Fields\Traits\DefaultUitype;
use Uccello\Core\Fields\Traits\UccelloUitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;

class DateTime implements Uitype
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
        return 'text';
    }

    /**
     * Returns default icon.
     *
     * @return string|null
     */
    public function getDefaultIcon() : ?string
    {
        return 'date_range';
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
        $options[ 'attr' ] = [ 'class' => 'form-control datetimepicker' ];

        return $options;
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
        return $table->datetime($this->getDefaultDatabaseColumn($field));
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
        return "\$table->datetime('$column')";
    }
}