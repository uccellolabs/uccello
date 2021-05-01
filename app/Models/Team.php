<?php

namespace Uccello\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Uccello\Core\Database\Eloquent\Model;
use Uccello\Core\Database\Factories\TeamFactory;
use Uccello\Core\Support\Traits\UccelloModule;

class Team extends Model
{
    use HasFactory, SoftDeletes, UccelloModule;

    public $table = 'teams';

    public $fillable = [
        'name',
        'description',
        'data',
        'workspace_id',
    ];

    public $casts = [
        'data' => 'object',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return TeamFactory::new();
    }
}
