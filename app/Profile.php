<?php

namespace Sardoj\Uccello;

use Sardoj\Uccello\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';

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
