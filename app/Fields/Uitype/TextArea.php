<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Models\Field;

class Textarea extends Text implements Uitype
{
    /**
     * Returns field type used by Form builder.
     *
     * @return string
     */
    public function getFormType(): string
    {
        return 'textarea';
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
        return $table->text($this->getDefaultDatabaseColumn($field));
    }
}