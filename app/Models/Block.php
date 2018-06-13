<?php

namespace Sardoj\Uccello\Models;

use Sardoj\Uccello\Database\Eloquent\Model;

class Block extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blocks';

    public function tab()
    {
        return $this->belongsTo(Tab::class);
    }

    public function fields()
    {
        return $this->hasMany(Field::class)->orderBy('sequence');
    }
}
