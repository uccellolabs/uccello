<?php

namespace Sardoj\Uccello;

use Sardoj\Uccello\Database\Eloquent\Model;

class Filter extends Model
{
    protected $table = 'filters';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'columns' => 'object',
        'conditions' => 'object',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
