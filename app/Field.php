<?php

namespace Sardoj\Uccello;

use Sardoj\Uccello\Database\Eloquent\Model;

class Field extends Model
{
    protected $table = 'fields';

    public function block()
    {
        return $this->belongsTo(Block::class);
    }
}
