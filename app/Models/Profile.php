<?php

namespace Sardoj\Uccello\Models;

use Sardoj\Uccello\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profiles';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, $this->tablePrefix . 'profiles_roles');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
