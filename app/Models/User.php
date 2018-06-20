<?php

namespace Sardoj\Uccello\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function privileges()
    {
        return $this->hasMany(Privilege::class);
    }

    public function canAdmin(Domain $domain, Module $module)
    {
        return $this->is_admin;
    }

    public function canCreate(Domain $domain, Module $module)
    {
        return true;
    }

    public function canRetrieve(Domain $domain, Module $module)
    {
        return true;
    }

    public function canUpdate(Domain $domain, Module $module)
    {
        return true;
    }

    public function canDelete(Domain $domain, Module $module)
    {
        return true;
    }

    public function canViewMap(Domain $domain, Module $module)
    {
        return true;
    }
}
