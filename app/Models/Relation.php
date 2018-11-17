<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Relation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'relations';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'object',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function relatedModule()
    {
        return $this->belongsTo(Module::class);
    }

    public function relatedlist()
    {
        return $this->belongsTo(Relatedlist::class);
    }
}
