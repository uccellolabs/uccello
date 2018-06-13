<?php

namespace Sardoj\Uccello\Models;

use Sardoj\Uccello\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    public function children()
    {
        return $this->hasMany(self::class);
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function privileges()
    {
        return $this->hasMany(Privilege::class);
    }

    public function profiles()
    {
        return $this->belongsToMany(Role::class, $this->tablePrefix . 'profiles_roles');
    }
}
