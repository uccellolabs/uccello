<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Displaytype extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'displaytypes';

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
        $modelClass = $this->model_class;

        if (class_exists($modelClass)) {
            return new $modelClass();
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
        return $this->getInstance()->isListable() ?? false;
    }

    /**
     * Checks if the field can be displayed in Detail view.
     *
     * @return boolean
     */
    public function isDetailable() : bool
    {
        return $this->getInstance()->isDetailable() ?? false;
    }

    /**
     * Checks if the field can be displayed in Edit view (create mode).
     *
     * @return boolean
     */
    public function isCreateable() : bool
    {
        return $this->getInstance()->isCreateable() ?? false;
    }

    /**
     * Checks if the field can be displayed in Edit view (edit mode).
     *
     * @return boolean
     */
    public function isEditable() : bool
    {
        return $this->getInstance()->isEditable() ?? false;
    }
}
