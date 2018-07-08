<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Field extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fields';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'object',
    ];

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function uitype()
    {
        return $this->belongsTo(Uitype::class);
    }

    public function displaytype()
    {
        return $this->belongsTo(Displaytype::class);
    }

    /**
     * Returns overrided label if defined, else default one.
     * Default: field.fieldName
     *
     * @return string
     */
    public function getLabelAttribute() : string
    {
        return $this->data->label ?? 'field.' . $this->name;
    }

    /**
     * Returns overrided column name if defined, else default one.
     * The related uitype defines default column name.
     *
     * @return string
     */
    public function getColumnAttribute() : string
    {
        if ($this->data->column ?? false) {
            $column = $this->data->column;
        } else {
            $uitypeClass = $this->uitype->class;
            $uitype = new $uitypeClass();
            $column = $uitype->getDefaultColumn($this);
        }

        return $column;
    }

    /**
     * Returns default icon if defined, else checks if an icon is related to the uitype.
     *
     * @return string|null
     */
    public function getIconAttribute() : ?string
    {
        if ($this->data->icon ?? false) {
            $icon = $this->data->icon;

        } else {
            $uitypeClass = $this->uitype->class;
            $uitype = new $uitypeClass();
            $icon = $uitype->getDefaultIcon();
        }

        return $icon;
    }

    /**
     * Checks if the field can be displayed in List view.
     *
     * @return boolean
     */
    public function isListable() : bool
    {
        return $this->displaytype->isListable();
    }

    /**
     * Checks if the field can be displayed in Detail view.
     *
     * @return boolean
     */
    public function isDetailable() : bool
    {
        return $this->displaytype->isDetailable();
    }

    /**
     * Checks if the field can be displayed in Edit view (create mode).
     *
     * @return boolean
     */
    public function isCreateable() : bool
    {
        return $this->displaytype->isCreateable();
    }

    /**
     * Checks if the field can be displayed in Edit view (edit mode).
     *
     * @return boolean
     */
    public function isEditable() : bool
    {
        return $this->displaytype->isEditable();
    }

    /**
     * Checks if the field can be displayed is hidden.
     * A hidden field can be displayed in List view.
     *
     * @return boolean
     */
    public function isHidden() : bool
    {
        return $this->displaytype->isHidden();
    }
}
