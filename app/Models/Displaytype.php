<?php

namespace Uccello\Core\Models;

use Illuminate\Support\Facades\Cache;
use Uccello\Core\Database\Eloquent\Model;

class Displaytype extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'displaytypes';

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    /**
     * Returns an instance of the display type if the related class exists
     *
     * @return mixed
     */
    public function getInstance()
    {
        $class = $this->class;

        if (class_exists($class)) {
            return new $class();
        } else {
            return null;
        }
    }

    /**
     * Checks if the field can be displayed in List view.
     *
     * @return boolean
     */
    public function isListable() : bool
    {
        return Cache::rememberForever('displaytype_'.$this->id.'_is_listable', function () {
            return $this->getInstance()->isListable();
        });
    }

    /**
     * Checks if the field can be displayed in Detail view.
     *
     * @return boolean
     */
    public function isDetailable() : bool
    {
        return Cache::rememberForever('displaytype_'.$this->id.'_is_detailable', function () {
            return $this->getInstance()->isDetailable();
        });
    }

    /**
     * Checks if the field can be displayed in Edit view (create mode).
     *
     * @return boolean
     */
    public function isCreateable() : bool
    {
        return Cache::rememberForever('displaytype_'.$this->id.'_is_createable', function () {
            return $this->getInstance()->isCreateable();
        });
    }

    /**
     * Checks if the field can be displayed in Edit view (edit mode).
     *
     * @return boolean
     */
    public function isEditable() : bool
    {
        return Cache::rememberForever('displaytype_'.$this->id.'_is_editable', function () {
            return $this->getInstance()->isEditable();
        });
    }
}
