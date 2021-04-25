<?php

namespace Uccello\Core\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Uccello\Core\Database\Eloquent\Model;
use Uccello\Core\Support\Traits\UccelloModule;

class Domain extends Model
{
    use SoftDeletes;
    use Sluggable;
    use UccelloModule;

    public $table = 'domains';

    public $fillable = [
        'name',
        'data',
    ];

    public $casts = [
        'data' => 'object',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true,
                'includeTrashed' => false
            ]
        ];
    }
}
