<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Uitype extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'uitypes';

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    /**
     * Returns uitype package name
     *
     * @return string|null
     */
    public function getPackageAttribute() : ?string
    {
        $uitypeClass = $this->class;
        return (new $uitypeClass())->package;
    }

    /**
     * Returns value to display.
     *
     * @param Field $field
     * @param mixed $record
     * @return string
     */
    public function getDisplayedValue(Field $field, $record) : string
    {
        $uitypeClass = $this->class;
        return (new $uitypeClass())->getDisplayedValue($field, $record);
    }
}
