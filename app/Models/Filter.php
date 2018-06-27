<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Filter extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
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
