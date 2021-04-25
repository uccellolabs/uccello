<?php

namespace Uccello\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Uccello\Core\Database\Eloquent\Model;
use Uccello\Core\Database\Factories\ProfileFactory;
use Uccello\Core\Support\Traits\UccelloModule;

class Profile extends Model
{
    use HasFactory, SoftDeletes, UccelloModule;

    public $table = 'profiles';

    public $fillable = [
        'name',
        'description',
        'data',
        'domain_id',
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
        return ProfileFactory::new();
    }
}
