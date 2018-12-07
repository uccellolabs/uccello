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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label', 'icon', 'description', 'sequence', 'module_id', 'tab_id'
    ];

    protected function setTablePrefix()
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
}
