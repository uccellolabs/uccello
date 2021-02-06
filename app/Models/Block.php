<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Block extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blocks';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'object',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'module_id',
        'tab_id',
        'label',
        'icon',
        'description',
        'sequence',
        'data',
    ];

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function tab()
    {
        return $this->belongsTo(Tab::class);
    }

    public function fields()
    {
        return $this->hasMany(Field::class)->orderBy('sequence');
    }

    public function getDescriptionAttribute()
    {
        return $this->data->description ?? null;
    }

    /**
     * Checks if the block contains at least one field visible accordin to view name.
     *
     * @param string $viewName
     *
     * @return boolean
     */
    public function hasVisibleFields($viewName)
    {
        if ($viewName === 'detail') {
            return $this->hasVisibleFieldsInDetailView();
        } elseif ($viewName === 'edit') {
            return $this->hasVisibleFieldsInEditView();
        } else {
            throw new \Exception("View $viewName is not supported");
        }
    }

    /**
     * Checks if the block contains at least one field detailable.
     *
     * @return boolean
     */
    public function hasVisibleFieldsInDetailView()
    {
        foreach ($this->fields as $field) {
            if ($field->isDetailable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the block contains at least one field editable.
     *
     * @return boolean
     */
    public function hasVisibleFieldsInEditView()
    {
        foreach ($this->fields as $field) {
            if ($field->isEditable()) {
                return true;
            }
        }

        return false;
    }
}
