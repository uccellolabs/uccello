<?php

namespace Sardoj\Uccello;

use Sardoj\Uccello\Database\Eloquent\Model;

class Tab extends Model
{
    protected $table = 'tabs';

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function blocks()
    {
        return $this->hasMany(Block::class);
    }
}
