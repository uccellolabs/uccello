<?php

namespace Sardoj\Uccello;

use Sardoj\Uccello\Database\Eloquent\Model;

class Block extends Model
{
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
