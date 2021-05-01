<?php

namespace Uccello\Core\Models;

use Uccello\Core\Database\Eloquent\Model;

class Uitype extends Model
{
    public $table = 'uitypes';

    public $fillable = [
        'name',
        'class',
        'data',
    ];

    public $casts = [
        'data' => 'object',
    ];
}
