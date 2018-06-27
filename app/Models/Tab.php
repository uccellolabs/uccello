<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Tab extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tabs';

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function blocks()
    {
        return $this->hasMany(Block::class)->orderBy('sequence');
    }
}
