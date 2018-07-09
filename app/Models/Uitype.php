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
     * Returns formatted value to display.
     *
     * @param Field $field
     * @param mixed $record
     * @return string
     */
    public function getFormattedValueToDisplay(Field $field, $record) : string
    {
        $uitypeClass = $this->class;
        return (new $uitypeClass())->getFormattedValueToDisplay($field, $record);
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
        $uitypeClass = $this->class;
        return (new $uitypeClass())->getFormattedValueToSave($field, $value, $record, $domain, $module);
    }
}
