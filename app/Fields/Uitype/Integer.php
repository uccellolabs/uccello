<?php

namespace Uccello\Core\Fields\Uitype;

use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Contracts\Field\Uitype;
use Uccello\Core\Models\Field;

class Integer extends Number implements Uitype
{
    /**
     * Create field column in the module table
     *
     * @param Field $field
     * @param Blueprint $table
     * @return \Illuminate\Support\Fluent
     */
    public function createFieldColumn(Field $field, Blueprint $table)
    {
        return $table->decimal($this->getDefaultDatabaseColumn($field));
    }
}