<?php

namespace Uccello\Core\Fields\Uitype;

use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;
use Illuminate\Database\Schema\Blueprint;


class Time extends DateTime implements Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @return string
     */
    public function getFormType(): string
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
        return 'access_time';
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
        $options['attr'] = ['class' => 'form-control timepicker'];

        return $options;
    }

    /**
     * Create field column in the module table
     *
     * @param Field $field
     * @param Blueprint $table
     * @return \Illuminate\Support\Fluent
     */
    public function createFieldColumn(Field $field, Blueprint $table)
    {
        return $table->time($this->getDefaultDatabaseColumn($field));
    }
}